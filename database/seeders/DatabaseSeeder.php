<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {


    /**
     * @throws \Exception
     */
    public function run(): void
    {
        //app(LanguageSeeder::class)->run();
        app(PostSeeder::class)->run();
        app(TagSeeder::class)->run();

    }
}
