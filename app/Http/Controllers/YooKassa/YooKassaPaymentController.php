<?php

namespace App\Http\Controllers\YooKassa;

use App\Http\Controllers\Controller;
use App\Http\Requests\YooKassa\YooKassaPaymentStatusRequest;
use App\Http\Requests\YooKassa\YooKassaRefundRequest;
use App\Models\UrlPayment;
use App\Services\YooKassa\YooKassaClient;
use Illuminate\Http\JsonResponse;

final class YooKassaPaymentController extends Controller
{
    public function status(YooKassaPaymentStatusRequest $request, YooKassaClient $client): JsonResponse
    {
        $urlPayment = UrlPayment::query()->findOrFail((int) $request->validated('url_payment_id'));

        if ((int) $urlPayment->payment_method_id !== 4) {
            return response()->json(['message' => 'Not a YooKassa payment'], 422);
        }

        $payment = $client->getPayment((string) $urlPayment->system_id);

        $urlPayment->system_status = $payment['status'] ?? $urlPayment->system_status;
        $urlPayment->provider_payload = $payment;
        if (($payment['status'] ?? null) === 'succeeded') {
            $urlPayment->payed ??= now();
        }
        if (($payment['status'] ?? null) === 'canceled') {
            $urlPayment->system_status = 'forgotten';
        }
        $urlPayment->save();

        return response()->json($payment);
    }

    public function refund(YooKassaRefundRequest $request, YooKassaClient $client): JsonResponse
    {
        $validated = $request->validated();
        $urlPayment = UrlPayment::query()->findOrFail((int) $validated['url_payment_id']);

        if ((int) $urlPayment->payment_method_id !== 4) {
            return response()->json(['message' => 'Not a YooKassa payment'], 422);
        }

        $amount = (float) ($validated['amount'] ?? $urlPayment->total);
        $idempotenceKey = $client->generateIdempotenceKey();

        $payload = [
            'payment_id' => (string) $urlPayment->system_id,
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency' => 'RUB',
            ],
        ];
        if (!empty($validated['description'])) {
            $payload['description'] = $validated['description'];
        }

        $refund = $client->createRefund($payload, $idempotenceKey);

        $urlPayment->idempotence_key = $idempotenceKey;
        $urlPayment->refund_id = $refund['id'] ?? $urlPayment->refund_id;
        $urlPayment->provider_payload = $refund;
        if (($refund['status'] ?? null) === 'succeeded') {
            $urlPayment->refunded_at ??= now();
        }
        $urlPayment->save();

        return response()->json($refund);
    }
}

