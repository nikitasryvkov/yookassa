<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\PaymentPoint;
use App\Models\User;
use App\Models\UserType;
use App\Repositories\AgentCommissionsRepository;
use App\Repositories\BotPaymentsRepository;
use App\Repositories\TochkaBank\TochkaBankPaymentsRepository;
use Illuminate\Support\Facades\Log;

class CrmHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        TochkaBankPaymentsRepository $tochkaBankPaymentsRepository,
        BotPaymentsRepository $botPaymentsRepository,
        AgentCommissionsRepository $agentCommissionsRepository,
    )
    {

        $data['payments'] = $tochkaBankPaymentsRepository->getTodayPayments();
        $data['types'] = $tochkaBankPaymentsRepository->getPaymentTypes();
        $user = auth()->user();
        $data['botPayments'] = $user->isAdmin()
            ? $botPaymentsRepository->getTodayPayments()
            : $botPaymentsRepository->getMyTodayPayments($user->id);
        $data['paymentPoints'] = PaymentPoint::query()->get()->keyBy('id');
        $data['users'] = User::query()->get()->keyBy('id');
        $data['userTypes'] = UserType::query()->get()->keyBy('id');
        $data['paymentMethods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['accountId'] = $user->account_number . '/' . $user->bic;
        $data['commissionSum'] = $agentCommissionsRepository->getCommissionToPay($user->id);
        return view('welcome', $data);
    }
}
