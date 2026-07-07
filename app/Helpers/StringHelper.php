<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Romanize a name (convert to ASCII/latin characters)
     * Uses basic character mapping for common Indonesian characters
     */
    public static function romanize(string $name): string
    {
        // Convert to lowercase first
        $romanized = mb_strtolower($name, 'UTF-8');

        // Common Indonesian/European character mappings
        $replacements = [
            // Indonesian vowels with diacritics
            'ā' => 'a', 'á' => 'a', 'ǎ' => 'a', 'â' => 'a', 'à' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'ē' => 'e', 'é' => 'e', 'ě' => 'e', 'ê' => 'e', 'è' => 'e', 'ë' => 'e',
            'ī' => 'i', 'í' => 'i', 'ǐ' => 'i', 'î' => 'i', 'ì' => 'i', 'ï' => 'i',
            'ō' => 'o', 'ó' => 'o', 'ǒ' => 'o', 'ô' => 'o', 'ò' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ū' => 'u', 'ú' => 'u', 'ǔ' => 'u', 'û' => 'u', 'ù' => 'u', 'ü' => 'u',
            'ñ' => 'n', 'ń' => 'n', 'ń' => 'n',
            'ç' => 'c', 'ć' => 'c', 'č' => 'c',
            'ş' => 's', 'ś' => 's', 'š' => 's',
            'ž' => 'z', 'ź' => 'z', 'ż' => 'z',
            'æ' => 'ae', 'œ' => 'oe',
            'ß' => 'ss',
            'đ' => 'd', 'ď' => 'd',
            'ł' => 'l', 'ł' => 'l',
            'ř' => 'r', 'ř' => 'r',
            'ý' => 'y', 'ÿ' => 'y',
            'ă' => 'a', 'â' => 'a', 'ș' => 's', 'ț' => 't', 'ț' => 't',
            // Indonesian specific
            'ı' => 'i', 'ğ' => 'g', 'ü' => 'u', 'ş' => 's', 'ö' => 'o', 'ç' => 'c',
        ];

        $romanized = strtr($romanized, $replacements);

        // Remove any remaining non-ASCII characters (except basic punctuation)
        $romanized = preg_replace('/[^\x20-\x7E]/', '', $romanized);

        return $romanized;
    }

    /**
     * Generate a username from a full name
     * Takes first part of name + last name, romanized, max 12 chars
     */
    public static function generateUsername(string $fullName): string
    {
        $parts = explode(' ', trim($fullName));
        $romanizedParts = array_map([self::class, 'romanize'], $parts);

        // Filter out empty parts
        $romanizedParts = array_filter($romanizedParts, fn ($p) => ! empty($p));

        if (count($romanizedParts) === 0) {
            return 'user'.time();
        }

        if (count($romanizedParts) === 1) {
            return substr($romanizedParts[0], 0, 12);
        }

        // Combine first name (first part) + last name (last part)
        $firstName = array_shift($romanizedParts);
        $lastName = array_pop($romanizedParts);

        $username = $firstName.$lastName;

        // Ensure max 12 characters
        return substr($username, 0, 12);
    }

    /**
     * Generate a password from the username
     * Takes the romanized username and uses it directly as password
     */
    public static function generatePassword(string $username): string
    {
        return $username;
    }

    /**
     * Sanitize a string for use as username (remove special chars, etc)
     */
    public static function sanitizeUsername(string $username): string
    {
        // Only keep alphanumeric characters and underscores
        return preg_replace('/[^a-z0-9_]/', '', $username);
    }

    /**
     * Make username unique by appending a number if it exists
     */
    public static function makeUsernameUnique(string $baseUsername, callable $existsCheck): string
    {
        $username = $baseUsername;
        $counter = 1;

        while ($existsCheck($username)) {
            $suffix = (string) $counter;
            $maxBaseLength = 12 - strlen($suffix);
            $username = substr($baseUsername, 0, $maxBaseLength).$suffix;
            $counter++;
        }

        return $username;
    }
}
