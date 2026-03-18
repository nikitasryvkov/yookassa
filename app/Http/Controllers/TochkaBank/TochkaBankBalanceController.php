<?php

namespace App\Http\Controllers\TochkaBank;

use App\Http\Controllers\Controller;
use App\Items\Result;
use App\Services\TochkaBank\TbApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TochkaBankBalanceController extends Controller
{
    public function getBalance(
        Request $request,
        TbApiService $tbApiService
    ): JsonResponse
    {
        $result = new Result();
        $response = [];
        try {
            $response = $tbApiService->getAccountBalance($request->input('id'));
            $result->data['balance'] = 0;
            if(isset($response['Data']['Balance']) && is_array($response['Data']['Balance'])) {
                foreach ($response['Data']['Balance'] as $item) {
                    if(isset($item['type']) && $item['type'] == 'ClosingAvailable' && isset($item['Amount']['amount'])) {
                        $result->data['balance'] = $item['Amount']['amount'];
                        break;
                    }

                    if(isset($item['type']) && $item['type'] == 'OpeningAvailable' && isset($item['Amount']['amount'])) {
                        $result->data['balance'] = $item['Amount']['amount'];
                    }
                }
            }
        } catch (Throwable $e) {
            $result->setError($e->getMessage());
            Log::error("Ошибка получения баланса: " . $e->getMessage(), $response);
        }

        return response()->json($result);
    }
}
