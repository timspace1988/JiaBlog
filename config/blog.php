<?php
return [
    'title' => 'My Blog',
    'posts_per_page' => 5,
    'uploads' => [
        // 'storage' => 'local',
        // 'webpath' => '/uploads',
        'storage' => 's3',
        'webpath' => getenv('AWS_URL'),//'https://s3.us-east-2.amazonaws.com/jiablog',
    ],
];
