<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PhilSMS Service Provider
 * Handles SMS notifications using the PhilSMS API v3
 */
class SmsService
{
    protected $provider;
    protected $apiToken;
    protected $apiKey;
    protected $apiUrl;
    protected $senderId;
    protected $semaphoreApiKey;
    protected $semaphoreUrl;
    protected $semaphoreSenderName;

    public function __construct()
    {
        $this->provider = strtolower(trim((string) (
            config('services.sms.provider')
            ?: env('SMS_PROVIDER')
            ?: 'philsms'
        )));

        // Prefer config values so cached config and env stay in sync.
        $this->apiToken = trim((string) (
            config('services.philsms.token')
            ?: config('services.philsms.key')
            ?: env('PHILSMS_API_TOKEN')
            ?: env('PHILSMS_API_KEY')
        ));
        $this->apiKey = trim((string) (
            config('services.philsms.key')
            ?: env('PHILSMS_API_KEY')
        ));
        $this->apiUrl = trim((string) (
            config('services.philsms.url')
            ?: env('PHILSMS_URL')
            ?: 'https://dashboard.philsms.com/api/v3/sms/send'
        ));
        $this->senderId = trim((string) (
            config('services.philsms.sender_id')
            ?: env('PHILSMS_SENDER')
            ?: ''
        ));

        $this->semaphoreApiKey = trim((string) (
            config('services.semaphore.api_key')
            ?: env('SEMAPHORE_API_KEY')
            ?: ''
        ));
        $this->semaphoreUrl = trim((string) (
            config('services.semaphore.url')
            ?: env('SEMAPHORE_URL')
            ?: 'https://api.semaphore.co/api/v4/messages'
        ));
        $this->semaphoreSenderName = trim((string) (
            config('services.semaphore.sender_name')
            ?: env('SEMAPHORE_SENDER_NAME')
            ?: ''
        ));
    }

    /**
     * Send SMS using PhilSMS API
     * 
     * @param string $recipient Phone number (e.g., 09171234567)
     * @param string $message The message content
     * @return array|mixed API Response
     */
    public function send(string $recipient, string $message)
    {
        if ($this->provider === 'semaphore') {
            return $this->sendViaSemaphore($recipient, $message);
        }

        if (empty($this->apiToken)) {
            Log::error("PhilSMS API Token is not configured.");
            return ['success' => false, 'error' => 'API Token missing'];
        }

        $recipient = $this->normalizeRecipient($recipient);

        if ($recipient === null) {
            Log::error('PhilSMS recipient format is invalid.', ['recipient' => $recipient]);
            return ['success' => false, 'error' => 'Invalid recipient number'];
        }

        $data = [
            "recipient" => $recipient,
            "type" => "plain",
            "message" => $message
        ];

        // Include sender_id if configured. 
        // Some accounts MUST send "PhilSMS" if no custom sender ID is set.
        if ($this->senderId !== '') {
            $data['sender_id'] = Str::limit($this->senderId, 11, '');
        }

        Log::info("Sending SMS via PhilSMS", [
            'recipient' => $recipient,
            'url' => $this->apiUrl,
            'sender_id' => $data['sender_id'] ?? '(using account default)',
            'token_prefix' => substr($this->apiToken, 0, 8) . '...',
            'message_preview' => Str::limit($message, 50),
        ]);

        $activeToken = $this->apiToken;
        $requestUrl = $this->apiUrl;

        [$response, $httpCode, $error] = $this->executeRequest($data, $activeToken, $requestUrl);

        $decodedResponse = json_decode((string) $response, true);
        $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));

        if (
            $error === ''
            && str_contains($messageText, 'unauthenticated')
            && $this->apiKey !== ''
            && $this->apiKey !== $this->apiToken
        ) {
            Log::warning('PhilSMS token rejected, retrying with PHILSMS_API_KEY.');
            $activeToken = $this->apiKey;
            [$response, $httpCode, $error] = $this->executeRequest($data, $activeToken, $requestUrl);
            $decodedResponse = json_decode((string) $response, true);
            $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));
        }

        if (
            $error === ''
            && isset($data['sender_id'])
            && (
                (str_contains($messageText, 'sender id') && str_contains($messageText, 'not authorized'))
                || str_contains($messageText, 'unauthenticated')
            )
        ) {
            Log::warning('PhilSMS request failed, retrying without sender_id.', [
                'error_message' => $messageText,
                'sender_id' => $data['sender_id'],
                'recipient' => $recipient,
            ]);

            $fallbackData = $data;
            unset($fallbackData['sender_id']);

            [$response, $httpCode, $error] = $this->executeRequest($fallbackData, $activeToken, $requestUrl);
            $decodedResponse = json_decode((string) $response, true);
            $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));
        }

        if ($error === '' && str_contains($messageText, 'unauthenticated')) {
            $candidateUrls = array_values(array_unique([
                $requestUrl,
                'https://dashboard.philsms.com/api/v3/sms/send',
                'https://app.philsms.com/api/v3/sms/send',
            ]));

            foreach ($candidateUrls as $candidateUrl) {
                if ($candidateUrl === $requestUrl) {
                    continue;
                }

                Log::warning('PhilSMS unauthenticated, retrying with alternative URL.', [
                    'original_url' => $requestUrl,
                    'fallback_url' => $candidateUrl,
                ]);

                [$response, $httpCode, $error] = $this->executeRequest($data, $activeToken, $candidateUrl);
                $requestUrl = $candidateUrl;
                $decodedResponse = json_decode((string) $response, true);
                $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));

                if (
                    $error === ''
                    && str_contains($messageText, 'unauthenticated')
                    && $this->apiKey !== ''
                    && $activeToken !== $this->apiKey
                ) {
                    Log::warning('PhilSMS alternative URL still unauthenticated, retrying with PHILSMS_API_KEY.');
                    $activeToken = $this->apiKey;
                    [$response, $httpCode, $error] = $this->executeRequest($data, $activeToken, $requestUrl);
                    $decodedResponse = json_decode((string) $response, true);
                    $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));
                }

                if (!str_contains($messageText, 'unauthenticated')) {
                    break;
                }
            }
        }

        // Handle errors or log response
        if ($error) {
            Log::error("PhilSMS cURL Error: " . $error, ['recipient' => $recipient]);
            return ['success' => false, 'error' => $error];
        }
        
        Log::info("PhilSMS Response", [
            'httpCode' => $httpCode,
            'response' => $decodedResponse,
            'recipient' => $recipient,
            'request_url' => $requestUrl,
            'raw' => $decodedResponse === null ? Str::limit((string) $response, 500) : null,
        ]);

        $apiStatus = strtolower((string) ($decodedResponse['status'] ?? ''));
        $successFlag = ($decodedResponse['success'] ?? null) === true;
        $messageText = strtolower((string) ($decodedResponse['message'] ?? ''));

        if (
            $httpCode >= 400
            || $apiStatus === 'error'
            || ($apiStatus !== 'success' && $successFlag === false && str_contains($messageText, 'error'))
            || str_contains($messageText, 'unauthenticated')
        ) {
            Log::error("PhilSMS API Error ($httpCode): " . $response, [
                'recipient' => $recipient,
                'api_url' => $requestUrl,
                'api_token_set' => !empty($this->apiToken),
                'decoded_response' => $decodedResponse,
            ]);
            return array_merge($decodedResponse ?? [], [
                'success' => false,
                'http_code' => $httpCode,
            ]);
        }

        if ($apiStatus === 'success' || $successFlag || $messageText === 'ok' || $messageText === 'success') {
            Log::info("SMS sent successfully to $recipient", ['response' => $decodedResponse]);
            return array_merge($decodedResponse ?? [], [
                'success' => true,
                'http_code' => $httpCode,
            ]);
        }

        Log::warning("PhilSMS API returned unknown response, treating as failure", ['response' => $decodedResponse, 'recipient' => $recipient]);
        return array_merge($decodedResponse ?? [], [
            'success' => false,
            'http_code' => $httpCode,
        ]);
    }

    private function sendViaSemaphore(string $recipient, string $message): array
    {
        if (empty($this->semaphoreApiKey)) {
            Log::error('Semaphore API key is not configured.');
            return ['success' => false, 'error' => 'Semaphore API key missing'];
        }

        $normalized = $this->normalizeRecipient($recipient);
        if ($normalized === null) {
            Log::error('Semaphore recipient format is invalid.', ['recipient' => $recipient]);
            return ['success' => false, 'error' => 'Invalid recipient number'];
        }

        $semaphoreNumber = $this->toLocalMobile($normalized);
        $data = [
            'apikey' => $this->semaphoreApiKey,
            'number' => $semaphoreNumber,
            'message' => $message,
        ];

        if ($this->semaphoreSenderName !== '') {
            $data['sendername'] = Str::limit($this->semaphoreSenderName, 11, '');
        }

        Log::info('Sending SMS via Semaphore', [
            'recipient' => $semaphoreNumber,
            'url' => $this->semaphoreUrl,
            'sender_name' => $data['sendername'] ?? '(default)',
            'message_preview' => Str::limit($message, 50),
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->semaphoreUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error !== '') {
            Log::error('Semaphore cURL error: ' . $error, ['recipient' => $semaphoreNumber]);
            return ['success' => false, 'error' => $error];
        }

        $decoded = json_decode((string) $response, true);
        Log::info('Semaphore response', [
            'httpCode' => $httpCode,
            'response' => $decoded,
            'recipient' => $semaphoreNumber,
            'raw' => $decoded === null ? Str::limit((string) $response, 500) : null,
        ]);

        $first = is_array($decoded) && isset($decoded[0]) && is_array($decoded[0]) ? $decoded[0] : null;
        $apiError = is_array($decoded) ? ($decoded['error'] ?? null) : null;

        if ($httpCode >= 400 || $apiError || !$first || empty($first['message_id'])) {
            $messageText = is_string($apiError) ? $apiError : ((string) ($first['status'] ?? 'SMS send failed'));
            return [
                'success' => false,
                'error' => $messageText,
                'http_code' => $httpCode,
                'response' => $decoded,
            ];
        }

        return [
            'success' => true,
            'http_code' => $httpCode,
            'message_id' => $first['message_id'] ?? null,
            'response' => $decoded,
        ];
    }

    private function executeRequest(array $data, string $token, ?string $url = null): array
    {
        $ch = curl_init();

        $effectiveUrl = $url ?: $this->apiUrl;

        curl_setopt($ch, CURLOPT_URL, $effectiveUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . trim($token),
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        return [(string) $response, (int) $httpCode, (string) $error];
    }

    private function normalizeRecipient(string $recipient): ?string
    {
        $normalized = preg_replace('/\D+/', '', trim($recipient));

        if (!$normalized) {
            return null;
        }

        if (str_starts_with($normalized, '0') && strlen($normalized) === 11) {
            return '63' . substr($normalized, 1);
        }

        if (str_starts_with($normalized, '63') && strlen($normalized) === 12) {
            return $normalized;
        }

        if (str_starts_with($normalized, '9') && strlen($normalized) === 10) {
            return '63' . $normalized;
        }

        return null;
    }

    private function toLocalMobile(string $recipient): string
    {
        if (str_starts_with($recipient, '63') && strlen($recipient) === 12) {
            return '0' . substr($recipient, 2);
        }

        return $recipient;
    }
}
