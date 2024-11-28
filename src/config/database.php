<?php

return [
    // 데이터베이스 설정
    'master' => [
        'host' => 'adieu2024.cluster-c26rhbuz6osw.ap-northeast-2.rds.amazonaws.com',
        'user' => 'web_adieuhackers',
        'password' => 'hacdkeb2024!!',
        'database' => 'adieu2024',
        'port' => '3306',
    ],
    'slave' => [
        'host' => 'adieu2024.cluster-ro-c26rhbuz6osw.ap-northeast-2.rds.amazonaws.com',
        'user' => 'web_adieuhackers',
        'password' => 'hacdkeb2024!!',
        'database' => 'adieu2024',
        'port' => '3306',
    ],
    // 로컬 테스트용
    'hacademia' => [
        'host' => '10.100.15.45',
        'user' => 'hacademia15',
        'password' => 'djgkrdnjs2015',
        'database' => 'hacademia',
        'port' => '3338',
    ],
];