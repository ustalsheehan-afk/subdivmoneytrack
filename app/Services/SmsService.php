<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

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
        // Get credentials from .env
        $this->apiToken = trim((string) env('PHILSMS_API_TOKEN'));
        $this->apiUrl = env('PHILSMS_URL', 'https://dashboard.philsms.com/api/v3/sms/send');
        $this->senderId = env('PHILSMS_SENDER', 'PhilSMS');
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

        // Prepare payload for PhilSMS - add apikey to payload
        $data = [
            "apikey" => $this->apiToken,
            "recipient" => $recipient,
            "sender_id" => $this->senderId,
            "type" => "plain",
            "message" => $message
        ];

        Log::info("Sending SMS via PhilSMS", ['recipient' => $recipient, 'url' => $this->apiUrl]);

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
        
        Log::info("PhilSMS Response", ['httpCode' => $httpCode, 'response' => $decodedResponse, 'recipient' => $recipient]);

        $status = strtolower($decodedResponse['status'] ?? '') === 'success';
        $successFlag = ($decodedResponse['success'] ?? null) === true;
        $messageText = strtolower($decodedResponse['message'] ?? '');

        if ($httpCode >= 400 || $status === false && $successFlag === false && str_contains($messageText, 'error')) {
            Log::error("PhilSMS API Error ($httpCode): " . $response, ['recipient' => $recipient]);
            return array_merge($decodedResponse ?? [], ['success' => false]);
        }

        if ($status || $successFlag || $messageText === 'ok' || $messageText === 'success') {
            Log::info("SMS sent successfully to $recipient", ['response' => $decodedResponse]);
            return array_merge($decodedResponse ?? [], ['success' => true]);
        }

        Log::warning("PhilSMS API returned unknown response, treating as failure", ['response' => $decodedResponse, 'recipient' => $recipient]);
        return array_merge($decodedResponse ?? [], ['success' => false]);
    }
}
