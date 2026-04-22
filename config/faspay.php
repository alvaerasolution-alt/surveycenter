<?php

return [
    /**
     * Faspay Merchant Configuration
     * For testing, use credentials from https://simulator.faspay.co.id/simulator
     */
    
    'merchant_id' => env('FASPAY_MERCHANT_ID', ''),
    'api_key' => env('FASPAY_API_KEY', ''),
    'password' => env('FASPAY_PASSWORD', ''),
    'user_id' => env('FASPAY_USER_ID', ''),
    
    /**
     * API Endpoints
     */
    'endpoints' => [
        'sandbox' => [
            'base_url'    => 'https://xpress-sandbox.faspay.co.id',
            'payment_url' => 'https://xpress-sandbox.faspay.co.id/v4/post',
        ],
        'production' => [
            'base_url'    => 'https://xpress.faspay.co.id',
            'payment_url' => 'https://xpress.faspay.co.id/v4/post',
        ],
    ],
    
    /**
     * Environment (sandbox or production)
     */
    'environment' => env('FASPAY_ENV', 'sandbox'),
    
    /**
     * Webhook URLs (will be registered in Faspay merchant dashboard)
     */
    'webhook_urls' => [
        'notification' => env('FASPAY_WEBHOOK_NOTIFICATION_URL', ''),
        'return' => env('FASPAY_WEBHOOK_RETURN_URL', ''),
    ],
    
    /**
     * Payment Channels
     * Set to true/false to enable/disable specific channels
     */
    'payment_channels' => [
        'virtual_account' => true,      // Bank VA (BCA, BNI, BRI, Mandiri, etc)
        'qris' => true,                 // QRIS
        'e_wallet' => true,             // E-wallet (GoPay, OVO, DANA, LinkAja)
        'bank_transfer' => true,        // Direct bank transfer
        'credit_card' => false,         // Credit card (if supported)
    ],
    
    /**
     * Supported Payment Methods
     */
    'supported_channels' => [
        'VA_BCA' => 'BCA Virtual Account',
        'VA_BNI' => 'BNI Virtual Account',
        'VA_BRI' => 'BRI Virtual Account',
        'VA_MANDIRI' => 'Mandiri Virtual Account',
        'VA_PERMATA' => 'Permata Virtual Account',
        'QRIS' => 'QRIS',
        'GOPAY' => 'GoPay',
        'OVO' => 'OVO',
        'DANA' => 'DANA',
        'LINK_AJA' => 'LinkAja',
    ],
    
    /**
     * Transaction Expiration (in minutes)
     */
    'invoice_expiration' => (int) env('FASPAY_INVOICE_EXPIRATION', 30),
    
    /**
     * Enable detailed logging
     */
    'logging' => [
        'enabled' => env('FASPAY_LOGGING_ENABLED', true),
        'channel' => 'stack',
    ],
];
