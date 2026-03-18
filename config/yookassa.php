<?php

return [
    'shop_id' => env('YOOKASSA_SHOP_ID'),
    'secret_key' => env('YOOKASSA_SECRET_KEY'),
    'test_mode' => (bool) env('YOOKASSA_TEST_MODE', false),

    // Куда ЮKassa редиректит пользователя после оплаты (return_url).
    // Можно указать отдельный URL, отличный от APP_URL.
    'return_url' => env('YOOKASSA_RETURN_URL', env('APP_URL')),

    // URL для вебхуков (не обязателен для работы, но удобен для генерации в UI/логах).
    'webhook_url' => env('YOOKASSA_WEBHOOK_URL'),

    // Необязательный shared secret для входящих вебхуков (проверяется по заголовку X-Webhook-Token).
    'webhook_token' => env('YOOKASSA_WEBHOOK_TOKEN'),

    // IP allowlist для webhook'ов (рекомендуется ЮKassa).
    // Источник: https://yookassa.ru/developers/using-api/webhooks
    'webhook_ip_allowlist' => array_filter(array_map('trim', explode(',', (string) env(
        'YOOKASSA_WEBHOOK_IP_ALLOWLIST',
        '185.71.76.0/27,185.71.77.0/27,77.75.153.0/25,77.75.156.11,77.75.156.35,77.75.154.128/25,2a02:5180::/32'
    )))),

    // 54‑ФЗ / Чеки: если магазин требует receipt при создании платежа.
    // Значения должны соответствовать настройкам вашей компании.
    'tax_system_code' => env('YOOKASSA_TAX_SYSTEM_CODE'), // 1..6 (в зависимости от системы налогообложения)
    'default_vat_code' => env('YOOKASSA_VAT_CODE'), // зависит от вашего НДС
];

