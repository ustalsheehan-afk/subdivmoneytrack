<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * PhilSMS Service Provider
 * Handles SMS notifications using the PhilSMS API v3
 */
class SmsService
{
    protected $apiKey;
    protected $apiUrl = "https://dashboard.philsms.com/api/v3/sms/send";

    public function __construct()
    {
        // Get API key from .env
        $this->apiKey = config('services.philsms.key') ?? env('PHILSMS_API_KEY');
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
        if (empty($this->apiKey)) {
            Log::error("PhilSMS API Key is not configured.");
            return ['error' => 'API Key missing'];
        }

        $data = [
            "recipient" => $recipient,
            "sender_id" => "PhilSMS", // Default sender ID
            "type"      => "plain",
            "message"   => $message
        ];

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->apiKey,
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
            Log::error("PhilSMS cURL Error: " . $error);
            return ['error' => $error];
        }

        $decodedResponse = json_decode($response, true);
        
        if ($httpCode >= 400) {
            Log::error("PhilSMS API Error ($httpCode): " . $response);
        }

        return $decodedResponse;
    }
}
