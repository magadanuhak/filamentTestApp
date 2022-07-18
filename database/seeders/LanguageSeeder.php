<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            'ro' => 'Romanian',
            'en' => 'English'
        ];

        foreach ($languages as $code => $language){
            Language::create([
                'name' => $language,
                'code' => $code
            ]);
        }
    }
}
