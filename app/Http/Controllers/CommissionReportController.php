<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\PaymentPoint;
use App\Models\User;
use App\Repositories\AgentCommissionsRepository;
use App\Services\TochkaBank\TbApiService;
use App\Services\TochkaBank\TbCommissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CommissionReportController extends Controller
{
    public function index(
        AgentCommissionsRepository $agentCommissionsRepository,
        Request $request,
    )
    {
        $user = auth()->user();
        $data['commissions'] = $user->isAdmin()
            ? $agentCommissionsRepository->getCommissions($request->all(), $request->input('page', 1))
            : $agentCommissionsRepository->getCommissions($request->all(), $request->input('page', 1), $user->id);

        $data['paymentPoints'] = PaymentPoint::query()->get()->keyBy('id');
        $data['users'] = User::query()->get()->keyBy('id');
        $data['paymentMethods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['filters'] = $request->input();
        $data['user'] = $user;
        $data['accountId'] = $user->account_number . '/' . $user->bic;
        $data['commissionSum'] = $agentCommissionsRepository->getCommissionToPay($user->id);
        return view('reports.commission.index', $data);
    }

    public function sendPaymentRequest(
        Request $request,
        AgentCommissionsRepository $agentCommissionsRepository,
        TbApiService $tbApiService,
    )
    {
        try {
            $commission = $agentCommissionsRepository->getById($request->post('id'));
            $result = (new TbCommissionService($commission, $commission->user, $tbApiService))
                ->createPaymentForSign();

        } catch (Throwable $e) {
            Log::error('Ошибка при запросе на выплату комиссии', [
                'message' => $e->getMessage(),
                'commission_id' => $request->post('id'),
            ]);
            $result = ['error' => $e->getMessage()];
        }

        return response()->json($result);
    }
}
