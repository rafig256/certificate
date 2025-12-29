<?php

namespace App\Services;

use Illuminate\Support\Arr;

class CertificateTextRenderer
{
    /**
     * Render HTML template and replace {{tokens}} using provided context.
     * - Only tokens registered in config/certificate_tokens.php are allowed.
     * - Token values are HTML-escaped for safety.
     */
    public function render(string $html, array $context, array $options = []): string
    {
        $tokens = config('certificate_tokens.tokens', []);

        $unknownTokenMode = $options['unknown_token_mode'] ?? 'empty';
        // modes: 'empty' | 'keep'

        // matches: {{ holder.full_name }} , {{event.title}}
        $pattern = '/\{\{\s*([a-zA-Z0-9_.]+)\s*\}\}/';

        return preg_replace_callback($pattern, function ($matches) use ($tokens, $context, $unknownTokenMode) {
            $tokenKey = $matches[1]; // e.g. holder.full_name

            // only allow tokens that exist in registry
            if (! array_key_exists($tokenKey, $tokens)) {
                return $unknownTokenMode === 'keep' ? $matches[0] : '';
            }

            $path = $tokens[$tokenKey]['path'] ?? $tokenKey;

            $value = Arr::get($context, $path);

            // Normalize null/arrays/objects to string
            if (is_null($value)) {
                $value = '';
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
            } elseif (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            } elseif (is_object($value)) {
                $value = method_exists($value, '__toString') ? (string) $value : '';
            }

            // Escape the value to prevent XSS (keep template HTML intact)
            return e((string) $value);
        }, $html);
    }
}
