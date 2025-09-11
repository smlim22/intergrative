<?php
namespace App\Services;

class SentimentService
{
    // Offline, lightweight sentiment analyzer using a tiny lexicon.
    // Returns: 'positive' | 'negative' | 'neutral' | 'N/A' (if empty)
    public static function analyze($text)
    {
        if (!is_string($text) || trim($text) === '') {
            return 'N/A';
        }

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
        if (!$tokens || count($tokens) === 0) {
            return 'neutral';
        }

        $posSet = array_flip($positive);
        $negSet = array_flip($negative);
        $negateWindow = 0; // number of tokens where negation applies
        $intensity = 1;    // multiplier for next sentiment word
        $score = 0;

        foreach ($tokens as $tok) {
            if ($tok === '') continue;

            // Handle negation markers
            if (isset($negators[$tok]) || str_ends_with($tok, "n't")) {
                $negateWindow = 3; // apply to next ~3 tokens
                continue;
            }

            // Handle intensifiers
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
