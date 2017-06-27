<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagTableSeeder extends Seeder
{
    /**
     * Seed the tags table
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();//remove all rows and reset the auto-incrementing ID to zero

        factory(Tag::class, 5)->create();
    }
}
