<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Swagger Optimization Strategy
    |--------------------------------------------------------------------------
    |
    | This configuration determines when Swagger annotations should be processed
    | and when they should be skipped for better development performance.
    |
    */

    'enabled' => [
        'development' => env('SWAGGER_DEV_ENABLED', false),
        'staging' => env('SWAGGER_STAGING_ENABLED', true),
        'production' => env('SWAGGER_PROD_ENABLED', true),
    ],

    'strategy' => env('SWAGGER_STRATEGY', 'conditional'), // conditional, always, never

    'conditional_rules' => [
        'skip_annotations_in_dev' => env('SWAGGER_SKIP_DEV_ANNOTATIONS', true),
        'generate_on_deploy' => env('SWAGGER_GENERATE_ON_DEPLOY', true),
        'cache_generated_docs' => env('SWAGGER_CACHE_DOCS', true),
    ],

    'performance' => [
        'skip_model_annotations' => env('SWAGGER_SKIP_MODELS', false),
        'skip_controller_annotations' => env('SWAGGER_SKIP_CONTROLLERS', false),
        'minimal_annotations' => env('SWAGGER_MINIMAL', false),
    ]
];
