<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Controller
{
    protected function resolveTranslationValue(array|string|null $value): string
    {
        if (is_array($value)) {
            return (string) (
                $value['bn']
                ?? $value['zh']
                ?? $value['en']
                ?? Arr::first(array_filter($value, static fn($item) => trim((string) $item) !== ''))
            );
        }

        return (string) $value;
    }

    protected function slugFromTranslation(array|string|null $value): string
    {
        return Str::slug($this->resolveTranslationValue($value));
    }
}
