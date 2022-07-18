<?php
declare(strict_types=1);


namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

trait TranslatableTrait
{
    /**
     * Get all the model's translations.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get the translation attribute.
     *
     * @return Translation
     */
    public function getTranslationAttribute()
    {
        return $this->translations->firstWhere('language', App::getLocale());
    }
}