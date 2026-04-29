<?php

return [
    'base_url' => env('SINGAPAY_BASE_URL', 'https://payment-b2b.singapay.id'),
    'account_id' => env('SINGAPAY_ACCOUNT_ID', ''),
    'client_id' => env('SINGAPAY_CLIENT_ID', ''),
    'client_secret' => env('SINGAPAY_CLIENT_SECRET', ''),
    'api_key' => env('SINGAPAY_API_KEY', ''),
    'invoice_prefix' => env('SINGAPAY_INVOICE_PREFIX', env('PAYMENT_INVOICE_PREFIX', 'TRX')),
];
