<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker){
    $images = [
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/about-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/contact-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/home-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/post-bg.jpg'
    ];
    $title = $faker->sentence(mt_rand(3, 10));
    return [
        'title' => $title,
        'subtitle' => str_limit($faker->sentence(mt_rand(10, 20)), 252),
        'page_image' => $images[mt_rand(0, 3)],
        'content_raw' => join("\n\n", $faker->paragraphs(mt_rand(3, 6))),
        'published_at' => $faker->dateTimeBetween('-1 month', '+3 days'),
        'meta_description' => "Meta for $title",
        'is_draft' => false,
    ];
});

$factory->define(App\Models\Tag::class, function($faker){
    $images = [
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/about-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/contact-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/home-bg.jpg',
        'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/post-bg.jpg'
    ];
    $word = $faker->word;
    return [
        'tag' => $word,
        'title' => ucfirst($word),
        'subtitle' => $faker->sentence,
        'page_image' => $images[mt_rand(0, 3)],
        'meta_description' => "Meta for $word",
        'reverse_direction' => false,
    ];
});
