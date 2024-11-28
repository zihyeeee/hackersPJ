<?php

return [
    'upload_path' => 'hackers2024',

    's3' => [
        'hackersac-cdn' => [
            'url' => "https://cdn.hackers.ac",  // s3 cloudfront 도메인
            'bucket' => 'hackersac-cdn', // 버킷명
            'factory' => [
                'suppress_php_deprecation_warning' => true,
                'region' => "ap-northeast-2", // 고정
                'version' => "latest", // 고정
                'signature' => "v4", // 고정
            ]
        ],

        'adieu2024' => [
            'url' => "https://d3mu22tz49dm8m.cloudfront.net",  // s3 cloudfront 도메인
            'bucket' => 'adieu2024', // 버킷명
            'factory' => [
                'suppress_php_deprecation_warning' => true,
                'region' => "ap-northeast-2", // 고정
                'version' => "latest", // 고정
                'signature' => "v4", // 고정
            ]
        ]
    ]
];
