<?php

if (! function_exists('ln')) {
    /**
     * Return text based on the current application locale.
     *
     * @param  string  $en
     * @param  string|null  $bn
     * @param  string|null  $zh
     * @return string
     */
    function ln(string $en, ?string $bn = null, ?string $zh = null): string
    {
        return match (app()->getLocale()) {
            'bn' => $bn ?? $en,
            'zh' => $zh ?? $en,
            default => $en,
        };
    }
}
