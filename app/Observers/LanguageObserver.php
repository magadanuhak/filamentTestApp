<?php

namespace App\Observers;

class LanguageObserver
{
    const LANGUAGE_CACHE_KEY = 'language';

    public function created()
    {
        cache()->delete(self::LANGUAGE_CACHE_KEY);
    }

    public function updated()
    {
        cache()->delete(self::LANGUAGE_CACHE_KEY);
    }

    public function deleted()
    {
        cache()->delete(self::LANGUAGE_CACHE_KEY);
    }
}
