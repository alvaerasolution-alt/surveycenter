<?php

$normalizeGateway = static function (?string $value): string {
    $gateway = strtolower(trim((string) $value));

    return $gateway === 'fastpay' ? 'faspay' : $gateway;
};

$configuredOrder = array_values(array_filter(array_map('trim', explode(',', (string) env('PAYMENT_GATEWAY_ORDER', 'singapay,faspay')))));
$normalizedOrder = [];

foreach ($configuredOrder as $gateway) {
    $normalized = $normalizeGateway($gateway);
    if ($normalized !== '' && !in_array($normalized, $normalizedOrder, true)) {
        $normalizedOrder[] = $normalized;
    }
}

return [
    'order' => $normalizedOrder,

    'default' => $normalizeGateway(env('PAYMENT_GATEWAY_DEFAULT', 'singapay')),

    'gateways' => [
        'singapay' => [
            'label' => 'SingaPay',
            'enabled' => env('PAYMENT_GATEWAY_SINGAPAY_ENABLED', true),
            'configured' => !empty(env('SINGAPAY_API_KEY'))
                && !empty(env('SINGAPAY_CLIENT_ID'))
                && !empty(env('SINGAPAY_CLIENT_SECRET'))
                && !empty(env('SINGAPAY_ACCOUNT_ID')),
        ],
        'faspay' => [
            'label' => 'Faspay',
            'enabled' => env('PAYMENT_GATEWAY_FASPAY_ENABLED', env('PAYMENT_GATEWAY_FASTPAY_ENABLED', true)),
            'configured' => !empty(env('FASPAY_MERCHANT_ID'))
                && !empty(env('FASPAY_USER_ID'))
                && !empty(env('FASPAY_PASSWORD')),
        ],
    ],
];
