<?php
declare(strict_types=1);


namespace App\Filament\Services;

use App\Models\Language;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class PostsTranslationsResourceGenerate
{
    public function __construct(protected $translatedModel, protected $form)
    {
    }

    public static function make($translatedModel)
    {
        return new self($translatedModel);
    }


    public function getFormsForTranslation()
    {
        $forms = [];

        $languages = cache()->set('language', Language::all()->toArray());
        foreach ($languages as $language) {

            $forms[] = Block::make($language['name'])
                            ->label("Translation for {$language['name']}")
                            ->schema([
                                TextInput::make("{$language['code']}_title")
                                         ->required()
                                         ->maxLength(60)
                                         ->minLength(3)
                                         ->reactive()
                                         ->afterStateUpdated(fn($state, callable $set) => $set('slug',
                                             Str::slug($state)))
                                ,
                                RichEditor::make("{$language['code']}_content")->required()->maxLength(32000),
                            ])
            ;
        }
    }

}