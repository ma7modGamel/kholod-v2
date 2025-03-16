<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
                    'host' => env('MAIL_HOST', 'mail.kholood.com'),
                    'port' => env('MAIL_PORT', 465),
                    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                    'timeout' => 60,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),

                    // 'host' => 'localhost',
                    // 'port' => '1025',
                    // 'encryption' => null,
                    // 'username' => null,
                    // 'password' => null,
                    // 'timeout' => 60,
                    // 'local_domain' => env('MAIL_EHLO_DOMAIN'),


        ],
    

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@kholood.com'),
        'name' => env('MAIL_FROM_NAME', 'Khoolod_System'),
    ],

];