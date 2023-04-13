<?php

if (! function_exists('photo_cdn')) {
    function photo_cdn(?string $path)
    {
        // if empty, return empty string
        if (empty($path)) {
            return null;
        }

        // if already a full URL, just return the URL
        if (Str::startsWith($path, ['http', '/'])) {
            return $path;
        }

        // if not spaces, use the url() method of the storage driver
        if (config('filesystems.default') != 'spaces') {
            return Storage::url($path);
        }

        // if spaces, use the Magis CDN
        return str_replace(
            parse_url(Storage::url(''), PHP_URL_HOST),
            'cdn.magis.marketing',
            Storage::url($path)
        );
    }
}