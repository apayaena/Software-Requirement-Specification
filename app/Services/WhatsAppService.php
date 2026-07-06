<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Send an automated WhatsApp notification.
     *
     * @param string $recipient Phone number in international format (e.g. 628123456789)
     * @param string $message Text content of the notification
     * @return bool
     */
    public function sendNotification(string $recipient, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('WhatsApp token is not configured. Message content: ' . $message);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $recipient,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp notification sent successfully to {$recipient}");
                return true;
            }

            Log::error('Fonnte API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Exception: ' . $e->getMessage());
            return false;
        }
    }
}
