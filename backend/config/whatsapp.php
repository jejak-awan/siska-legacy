<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp Business API integration
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    | Predefined message templates for different notification types
    |
    */

    'templates' => [
        'notification' => [
            'name' => 'notification',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, Anda memiliki notifikasi baru: {{2}}'
                ]
            ]
        ],
        'presensi_reminder' => [
            'name' => 'presensi_reminder',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, jangan lupa untuk melakukan presensi hari ini. Waktu presensi: {{2}}'
                ]
            ]
        ],
        'kredit_poin_approval' => [
            'name' => 'kredit_poin_approval',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, kredit poin Anda sebesar {{2}} poin telah disetujui.'
                ]
            ]
        ],
        'kredit_poin_rejection' => [
            'name' => 'kredit_poin_rejection',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, kredit poin Anda sebesar {{2}} poin telah ditolak. Alasan: {{3}}'
                ]
            ]
        ],
        'konseling_reminder' => [
            'name' => 'konseling_reminder',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, Anda memiliki jadwal konseling pada {{2}} pukul {{3}}.'
                ]
            ]
        ],
        'home_visit_reminder' => [
            'name' => 'home_visit_reminder',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, akan ada kunjungan rumah pada {{2}} pukul {{3}}.'
                ]
            ]
        ],
        'osis_activity' => [
            'name' => 'osis_activity',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, akan ada kegiatan OSIS "{{2}}" pada {{3}} di {{4}}.'
                ]
            ]
        ],
        'ekstrakurikuler_registration' => [
            'name' => 'ekstrakurikuler_registration',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'Halo {{1}}, pendaftaran Anda ke ekstrakurikuler "{{2}}" berhasil.'
                ]
            ]
        ],
        'system_alert' => [
            'name' => 'system_alert',
            'language' => 'id',
            'components' => [
                'body' => [
                    'text' => 'ALERT: {{1}} - {{2}}'
                ]
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for rate limiting to avoid API limits
    |
    */

    'rate_limiting' => [
        'enabled' => env('WHATSAPP_RATE_LIMITING_ENABLED', true),
        'max_messages_per_minute' => env('WHATSAPP_MAX_MESSAGES_PER_MINUTE', 20),
        'max_messages_per_hour' => env('WHATSAPP_MAX_MESSAGES_PER_HOUR', 1000),
        'delay_between_messages' => env('WHATSAPP_DELAY_BETWEEN_MESSAGES', 100000), // microseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for messages
    |
    */

    'message_settings' => [
        'default_language' => 'id',
        'max_message_length' => 4096,
        'enable_typing_indicator' => false,
        'enable_read_receipts' => true,
        'retry_failed_messages' => true,
        'max_retry_attempts' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for webhook handling
    |
    */

    'webhook' => [
        'enabled' => env('WHATSAPP_WEBHOOK_ENABLED', true),
        'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
        'process_incoming_messages' => env('WHATSAPP_PROCESS_INCOMING_MESSAGES', false),
        'auto_reply' => env('WHATSAPP_AUTO_REPLY', false),
        'auto_reply_message' => env('WHATSAPP_AUTO_REPLY_MESSAGE', 'Terima kasih telah menghubungi kami. Pesan Anda akan segera diproses.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for media messages
    |
    */

    'media' => [
        'max_file_size' => 16 * 1024 * 1024, // 16MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'upload_path' => 'whatsapp/media',
        'cdn_url' => env('WHATSAPP_CDN_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for logging WhatsApp messages
    |
    */

    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'log_all_messages' => env('WHATSAPP_LOG_ALL_MESSAGES', true),
        'log_failed_messages' => env('WHATSAPP_LOG_FAILED_MESSAGES', true),
        'log_webhook_events' => env('WHATSAPP_LOG_WEBHOOK_EVENTS', true),
        'retention_days' => env('WHATSAPP_LOG_RETENTION_DAYS', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for testing environment
    |
    */

    'testing' => [
        'enabled' => env('WHATSAPP_TESTING_ENABLED', false),
        'test_phone_number' => env('WHATSAPP_TEST_PHONE_NUMBER'),
        'mock_responses' => env('WHATSAPP_MOCK_RESPONSES', false),
        'simulate_delays' => env('WHATSAPP_SIMULATE_DELAYS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for security features
    |
    */

    'security' => [
        'verify_signature' => env('WHATSAPP_VERIFY_SIGNATURE', true),
        'allowed_ips' => env('WHATSAPP_ALLOWED_IPS', ''),
        'encrypt_messages' => env('WHATSAPP_ENCRYPT_MESSAGES', false),
        'require_authentication' => env('WHATSAPP_REQUIRE_AUTHENTICATION', true),
    ]
];
