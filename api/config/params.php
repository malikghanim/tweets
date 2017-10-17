<?php
return [
    'adminEmail' => 'support@maqtoo3.com',
    'siteUrl' => 'http://maqtoo3.com',
    'integrations' => [
        'google' => [
            'client_id' => '1027436742509-tocmg1388kljnjtdrqef64ko7rj8i1a5.apps.googleusercontent.com',
            'client_secret' => '1oi8cSlna_p6BwxGUDG5N0TF',
        ],
        'facebook' => [
            'client_id' => 'sasadsadadd',
            'client_secret' => 'dssadasdsad',
        ]
    ],
    'reCaptcha' => [
        'siteKey' => '6Lc-GCoUAAAAANtILuwBt1HUn96Kw8fs8k-c51XB',
        'secret' => '6Lc-GCoUAAAAAMTAB0Y7bIhiM1TLLkFOft63w8xY',
    ],
    'CDN_URL' => 'https://frontend.maqtoo3.dev',
    'CDN_VERSION' => '?v=1.3',
    'token_status' => ['valid' => 0, 'empty' => 1, 'expired' => 2, 'wrong' => 3],
    'user.passwordResetTokenExpire' => 3600,
];
