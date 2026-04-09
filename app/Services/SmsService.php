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
    protected $apiToken;
    protected $apiUrl;
    protected $senderId;

    public function __construct()
    {
        // Prefer config values so cached config and env stay in sync.
        $this->apiToken = trim((string) (
            config('services.philsms.token')
            ?: config('services.philsms.key')
            ?: env('PHILSMS_API_TOKEN')
            ?: env('PHILSMS_API_KEY')
        ));
        $this->apiUrl = trim((string) (
            config('services.philsms.url')
            ?: env('PHILSMS_URL')
            ?: 'https://app.philsms.com/api/v3/sms/send'
        ));
        $this->senderId = trim((string) (
            config('services.philsms.sender_id')
            ?: env('PHILSMS_SENDER')
            ?: 'PhilSMS'
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
            "sender_id" => Str::limit($this->senderId, 11, ''),
            "type" => "plain",
            "message" => $message
        ];

        Log::info("Sending SMS via PhilSMS", [
            'recipient' => $recipient,
            'url' => $this->apiUrl,
            'sender_id' => $data['sender_id'],
        ]);

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->apiToken,
            "Content-Type: application/json",
            "Accept: application/json"
        ]);

        // Execute cURL
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        // Handle errors or log response
        if ($error) {
            Log::error("PhilSMS cURL Error: " . $error, ['recipient' => $recipient]);
            return ['success' => false, 'error' => $error];
        }

        $decodedResponse = json_decode($response, true);
        
        Log::info("PhilSMS Response", [
            'httpCode' => $httpCode,
            'response' => $decodedResponse,
            'recipient' => $recipient,
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
            Log::error("PhilSMS API Error ($httpCode): " . $response, ['recipient' => $recipient]);
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
}
