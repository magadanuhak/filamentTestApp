<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $tags = [
            'new',
            'cool',
            'fake',
            'notInteresting'
        ];

        $posts = Post::all();
        foreach ($posts as $post) {

            $randomTags = Arr::random($tags, random_int(1,3));

            foreach ($randomTags as $tag){

                $created = Tag::create([
                    'text' => $tag,
                    'taggable_type' => Post::class,
                    'taggable_id' => $post->id
                ]);
            }

        }
    }
}
