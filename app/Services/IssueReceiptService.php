<?php

namespace App\Services;

use App\Enums\KassaDocTypes;
use App\Models\Receipt\ReceiptStatus;
use App\Services\ModuleKassa\ModuleKassaReceiptsApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IssueReceiptService
{
    public function __construct(
        private readonly ModuleKassaReceiptsApiService $kassaReceiptsApiService,
    )
    {
    }

    public function handle(array $post): array
    {
        $receiptService = new ReceiptService($post['type'], $post['id'], KassaDocTypes::SALE->value);
        $receiptService = $receiptService->getReceiptDTO();

        if(empty($receiptService)) {
            return $receiptService;
        }

        $responseArray = $this->kassaReceiptsApiService->issueReceipt($receiptService);

        Log::info('Ответ на запрос создания чека', $responseArray);

        if(empty($responseArray)) {
            return $receiptService;
        }

        ReceiptStatus::query()->create([
            'receipt_id' => $receiptService['id'],
            'status' => $responseArray['status'],
            'fn_state' => $responseArray['fnState'],
            'failure_info' => $responseArray['failureInfo'],
            'message' => $responseArray['message'],
            'time_status_changed' => Carbon::parse($responseArray['timeStatusChanged'])->format('Y-m-d H:i:s'),
        ]);

        return $receiptService;
    }
}
