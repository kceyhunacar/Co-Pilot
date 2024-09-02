<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ExpoPushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $data;

    public function __construct($title, $body, $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function sendExpoNotification($notifiable)
    {
        $tokens = $notifiable->expoPushTokens->pluck('expo_push_token')->toArray();

        $chunks = array_chunk($tokens, 100); // Expo maksimum 100 token kabul ediyor

        foreach ($chunks as $chunk) {
            $response = Http::post('https://exp.host/--/api/v2/push/send', [
                'to' => $chunk,
                'title' => $this->title,
                'body' => $this->body,
                'data' => $this->data,
                'sound' => 'default',
            ]);

            if ($response->failed()) {
                \Log::error('Push Notification Failed for chunk: ' . $response->body());
            }
        }
    }
    public function sendBulkNotifications($notifiables)
    {
        $allTokens = [];

        foreach ($notifiables as $notifiable) {
            $tokens = $notifiable->expoPushTokens->pluck('expo_push_token')->toArray();
            $allTokens = array_merge($allTokens, $tokens);
        }

        $chunks = array_chunk($allTokens, 100); // Expo maksimum 100 token kabul ediyor

        foreach ($chunks as $chunk) {
            $response = Http::post('https://exp.host/--/api/v2/push/send', [
                'to' => $chunk,
                'title' => $this->title,
                'body' => $this->body,
                'data' => $this->data,
                'sound' => 'default',
            ]);

            if ($response->failed()) {
                \Log::error('Push Notification Failed for chunk: ' . $response->body());
            }
        }
    }
    
}
