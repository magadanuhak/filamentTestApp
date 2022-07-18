<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $post = Post::create([
            'title' => 'Test title for post',
            'content' => '<html> <h1>test</h1></html>',
            'slug'=> 'slug-for-post',
            'main_image_url' => 'no_image_url',
            'country_id' => Country::query()->inRandomOrder()->first()->id
        ]);

       $post->translations()->create([
           'language' => 'ro',
           'content' => [
               'title' => 'titlu test pentru postare',
               'content' => 'continut test pentru postare'
           ]
       ]);
    }
}
