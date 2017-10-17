<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'redis' => [
          'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
          'database' => 5,
        ],
    ],
    'bootstrap' => ['gii'],
    'modules' =>[
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',            
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'authorization_code' => 'api\storage\oauth2\Redis',
                'access_token' => 'api\storage\oauth2\Redis',
                'client_credentials' => 'api\storage\oauth2\Redis',
                'client' => 'api\storage\oauth2\Redis',
                'refresh_token' => 'api\storage\oauth2\Redis',
                'user_credentials' => 'api\storage\oauth2\Redis',
                'public_key' => 'api\storage\oauth2\Redis',
                'jwt_bearer' => 'api\storage\oauth2\Redis',
                'scope' => 'api\storage\oauth2\Redis',
            ],
            'grantTypes' => [
                'client_credentials' => [
                    'class' => 'OAuth2\GrantType\ClientCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => false
                ],
                'authorization_code' => [
                    'class' => 'OAuth2\GrantType\AuthorizationCode',
                ]
            ]
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ]
];
