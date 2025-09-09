<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SentimentService
{
    public static function analyze($text)
    {
        if (empty($text)) return null;
        // Example: Text Processing API (free, no key required)
        $response = Http::asForm()->post('https://text-processing.com/api/sentiment/', [
            'text' => $text,
        ]);
        if ($response->ok()) {
            $data = $response->json();
            return $data['label'] ?? null;
        }
        return null;
    }
}
