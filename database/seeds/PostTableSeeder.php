<?php

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();//truncate(cut into short) existing records in posts table
        factory(Post::class, 20)->create();//create 20 fake posts data records(actually call post model factory 20 times)
    }
}
