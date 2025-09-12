<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * SentimentService
 *
 * Provides sentiment analysis for feedback comments with a pluggable driver model.
 * Current supported drivers:
 *  - offline (default): local lexicon + simple rules (no network, no API key)
 *  - azure: Azure Text Analytics Sentiment API (requires endpoint + key)
 *
 * Behavior:
 *  - Driver is selected via env SENTIMENT_DRIVER (see config/services.php 'sentiment').
 *  - If driver=azure but credentials are missing or the HTTP call fails, the service
 *    silently falls back to the offline analyzer so the UI always shows a result.
 *  - Return values are one of: 'positive' | 'neutral' | 'negative' | 'N/A' (empty text).
 *
 * How to enable Azure:
 *  1. Add to .env:
 *       SENTIMENT_DRIVER=azure
 *       AZURE_TEXT_ENDPOINT=https://<resource>.cognitiveservices.azure.com
 *       AZURE_TEXT_KEY=<key>
 *       SENTIMENT_LANGUAGE=en
 *  2. php artisan config:clear
 *  3. Reload feedback page as admin.
 *
 * Keeping this file with both implementations demonstrates web service integration
 * readiness while still functioning offline for environments without external access.
 */
class SentimentService
{
    // Entry point with driver switch and offline fallback
    /**
     * Public entry point. Chooses driver, attempts external API if configured,
     * then falls back to offline logic for resilience.
     */
    public static function analyze($text)
    {
        if (!is_string($text) || trim($text) === '') {
            return 'N/A';
        }

        $driver = config('services.sentiment.driver', 'offline');
        if ($driver === 'azure') {
            $res = self::analyzeWithAzure($text);
            if ($res !== null) return $res; // use API result if available
        }
        // Fallback to offline analyzer
        return self::analyzeOffline($text);
    }

    // Azure Text Analytics (Sentiment v3.2) minimal client
    /**
     * Azure Text Analytics integration.
     * Returns sentiment label or null on any failure (so caller can fallback).
     */
    protected static function analyzeWithAzure(string $text): ?string
    {
        $endpoint = rtrim(config('services.sentiment.azure.endpoint', ''), '/');
        $key = config('services.sentiment.azure.key');
        $language = config('services.sentiment.azure.language', 'en');
        if (!$endpoint || !$key) return null;

        $url = $endpoint . '/text/analytics/v3.2/sentiment';
        $payload = [
            'documents' => [[ 'id' => '1', 'language' => $language, 'text' => $text ]],
        ];

        try {
            $resp = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if (!$resp->ok()) return null;
            $data = $resp->json();
            $label = $data['documents'][0]['sentiment'] ?? null; // 'positive'|'neutral'|'negative'
            return $label ?: null;
        } catch (\Throwable $e) {
            return null; // swallow errors and fallback
        }
    }

    // Offline, lightweight analyzer
    /**
     * Offline lexicon-based heuristic analyzer.
     * Simple scoring with negation + intensifier handling.
     */
    protected static function analyzeOffline(string $text): string
    {
        $positive = [
            'good','great','excellent','amazing','awesome','love','loved','like','liked','pleasant','happy','fantastic','wonderful','perfect','nice','cool','best','enjoy','enjoyed','satisfied','recommend','positive','superb','incredible','beautiful','fast','clean','spacious','friendly'
        ];
        $negative = [
            'bad','terrible','awful','hate','hated','dislike','disliked','poor','worse','worst','dirty','slow','broken','rude','noisy','crowded','smelly','uncomfortable','disappointing','disappointed','horrible','issue','problem','negative'
        ];
        $negators = ['not','no','never',"n't"]; // handles simple negation
        $intensifiers = ['very','really','extremely','so','too'];

        $clean = strtolower($text);
        $clean = preg_replace('/[^a-z0-9\s\']+/', ' ', $clean);
        $tokens = preg_split('/\s+/', trim($clean));
        if (!$tokens or count($tokens) === 0) {
            return 'neutral';
        }

        $posSet = array_flip($positive);
        $negSet = array_flip($negative);
        $negateWindow = 0; // number of tokens where negation applies
        $intensity = 1;    // multiplier for next sentiment word
        $score = 0;

        foreach ($tokens as $tok) {
            if ($tok === '') continue;

            if (in_array($tok, $negators, true) || str_ends_with($tok, "n't")) {
                $negateWindow = 3; // apply to next ~3 tokens
                continue;
            }

            if (in_array($tok, $intensifiers, true)) {
                $intensity = 2; // next sentiment token gets doubled
                continue;
            }

            $val = 0;
            if (isset($posSet[$tok])) $val = 1;
            if (isset($negSet[$tok])) $val = -1;

            if ($val !== 0) {
                if ($negateWindow > 0) $val *= -1;
                $val *= $intensity;
                $score += $val;
                $intensity = 1; // reset after use
            }

            if ($negateWindow > 0) $negateWindow--;
        }

        if ($score > 0) return 'positive';
        if ($score < 0) return 'negative';
        return 'neutral';
    }
}
