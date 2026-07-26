<?php

namespace App\Support;

use Illuminate\Support\Str;

class RichText
{
    /**
     * Convert HTML content to clean plain text for card summaries, lists, and tree nodes.
     *
     * @param string|null $html
     * @param int|null $limit
     * @param string $end
     * @return string
     */
    public static function toPlainText(?string $html, ?int $limit = null, string $end = '...'): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // Replace block tags and breaks with a space to prevent merging adjacent words
        $text = preg_replace('/<\/(p|div|h[1-6]|li|tr|br\s*\/?)>/i', ' ', $html);
        $text = preg_replace('/<br\s*\/?>/i', ' ', $text);

        // Replace non-breaking spaces
        $text = str_replace(['&nbsp;', "\xC2\xA0"], ' ', $text);

        // Strip remaining HTML tags
        $text = strip_tags($text);

        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Normalize multiple whitespaces into a single space and trim
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if ($limit !== null && $limit > 0 && mb_strlen($text, 'UTF-8') > $limit) {
            return Str::limit($text, $limit, $end);
        }

        return $text;
    }
}
