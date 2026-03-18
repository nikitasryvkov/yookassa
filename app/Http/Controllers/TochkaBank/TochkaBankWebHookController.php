<?php

namespace App\Http\Controllers\TochkaBank;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentViaWebhook;
use App\Services\TochkaBank\WebhookService;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class TochkaBankWebHookController extends Controller
{
    //Вебхук /api/tb-wh для получения информации по платежам из точки
    public function getPayment(
        Request $request,
        WebhookService $webhookService,
    ): Response
    {
        $executed = RateLimiter::attempt(
            'tb webhook',
            $perMinute = 20,
            function() {

            }
        );

        if (! $executed) {
            return response('Too many requests', 429);
        }

        $entityBody = $request->getContent();

        // Публичный ключ Точки. Может быть получен из https://enter.tochka.com/doc/openapi/static/keys/public
        $json_key = '{"kty":"RSA","e":"AQAB","n":"rwm77av7GIttq-JF1itEgLCGEZW_zz16RlUQVYlLbJtyRSu61fCec_rroP6PxjXU2uLzUOaGaLgAPeUZAJrGuVp9nryKgbZceHckdHDYgJd9TsdJ1MYUsXaOb9joN9vmsCscBx1lwSlFQyNQsHUsrjuDk-opf6RCuazRQ9gkoDCX70HV8WBMFoVm-YWQKJHZEaIQxg_DU4gMFyKRkDGKsYKA0POL-UgWA1qkg6nHY5BOMKaqxbc5ky87muWB5nNk4mfmsckyFv9j1gBiXLKekA_y4UwG2o1pbOLpJS3bP_c95rm4M9ZBmGXqfOQhbjz8z-s9C11i-jmOQ2ByohS-ST3E5sqBzIsxxrxyQDTw--bZNhzpbciyYW4GfkkqyeYoOPd_84jPTBDKQXssvj8ZOj2XboS77tvEO1n1WlwUzh8HPCJod5_fEgSXuozpJtOggXBv0C2ps7yXlDZf-7Jar0UYc_NJEHJF-xShlqd6Q3sVL02PhSCM-ibn9DN9BKmD"}';
        $jwks = json_decode($json_key, true, 512, JSON_THROW_ON_ERROR);

        try {
            $decoded = JWT::decode($entityBody, JWK::parseKey($jwks,"RS256"));
        } catch (\UnexpectedValueException $e) {
            // Неверная подпись, вебхук не от Точки или с ним что-то не так
            Log::error('payment-webhook: ' . $e->getMessage());
            return response(null, 403);
        }

        ProcessPaymentViaWebhook::dispatch($webhookService, $decoded);

        return response(null, 200);
    }
}
