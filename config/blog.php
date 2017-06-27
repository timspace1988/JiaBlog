<?php
return [
    'name' => 'A beautiful blog',
    'title' => 'My Blog',
    'subtitle' => 'A clean blog written in Laravel 5.1',
    'description' => 'This is my meta description',
    'author' => 'Jia Liu',
    'page_image' => 'https://s3.us-east-2.amazonaws.com/jiablog/Header%20Images/home-bg.jpg',
    'posts_per_page' => 10,
    'uploads' => [
        // 'storage' => 'local',
        // 'webpath' => '/uploads',
        'storage' => 's3',
        'webpath' => getenv('AWS_URL'),//'https://s3.us-east-2.amazonaws.com/jiablog',
    ],
];
