<?php

namespace App\Http\Controllers\Receipt;

use App\Enums\KassaDocTypes;
use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptStatus;
use App\Services\IssueReceiptService;
use App\Services\ModuleKassa\ModuleKassaReceiptsApiService;
use App\Services\ReceiptService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{

    public function store(
        Request $request,
        IssueReceiptService $issueReceiptService,
    )
    {
        $post = $request->post();
        $receiptService = $issueReceiptService->handle($post);
        return response()->json($receiptService);
    }

    public function getStatus(
        Request $request,
        ModuleKassaReceiptsApiService $kassaReceiptsApiService,
    ): JsonResponse
    {
        $responseArray = $kassaReceiptsApiService->getReceiptStatus($request->post('id'));
        return response()->json($responseArray);
    }
}
