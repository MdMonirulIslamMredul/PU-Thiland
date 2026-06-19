<?php

namespace App\Support;

final class PhoneNumber
{
    public const COUNTRY_BD = 'BD';
    public const COUNTRY_CN = 'CN';

    public static function supportedCountries(): array
    {
        return [self::COUNTRY_BD, self::COUNTRY_CN];
    }

    public static function normalizeForCountry(string $country, ?string $value): ?string
    {
        $country = strtoupper(trim($country));

        return match ($country) {
            self::COUNTRY_BD => self::normalizeBangladesh($value),
            self::COUNTRY_CN => self::normalizeChina($value),
            default => null,
        };
    }

    public static function normalizeAndDetect(?string $value): ?array
    {
        $bd = self::normalizeBangladesh($value);
        if ($bd !== null) {
            return ['country' => self::COUNTRY_BD, 'e164' => $bd];
        }

        $cn = self::normalizeChina($value);
        if ($cn !== null) {
            return ['country' => self::COUNTRY_CN, 'e164' => $cn];
        }

        return null;
    }

    private static function normalizeBangladesh(?string $value): ?string
    {
        $value = self::sanitize($value);
        if ($value === null) {
            return null;
        }

        if (str_starts_with($value, '+880')) {
            $national = '0' . substr($value, 4);
        } elseif (str_starts_with($value, '+88')) {
            $national = '0' . substr($value, 3);
        } elseif (str_starts_with($value, '880')) {
            $national = '0' . substr($value, 3);
        } elseif (str_starts_with($value, '88')) {
            $national = '0' . substr($value, 2);
        } else {
            $national = $value;
        }

        if (! preg_match('/^01\d{9}$/', $national)) {
            return null;
        }

        return '+880' . substr($national, 1);
    }

    private static function normalizeChina(?string $value): ?string
    {
        $value = self::sanitize($value);
        if ($value === null) {
            return null;
        }

        if (str_starts_with($value, '+86')) {
            $national = substr($value, 3);
        } elseif (str_starts_with($value, '86') && strlen($value) >= 13) {
            $national = substr($value, 2);
        } else {
            $national = $value;
        }

        if (! preg_match('/^1\d{10}$/', $national)) {
            return null;
        }

        return '+86' . $national;
    }

    private static function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        $value = preg_replace('/[\s\-()]/', '', $value) ?? $value;

        return $value === '' ? null : $value;
    }
}
