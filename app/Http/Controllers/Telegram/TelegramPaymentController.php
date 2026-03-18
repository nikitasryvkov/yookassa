<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\PaymentPoint;
use App\Models\User;
use App\Models\UserType;
use App\Repositories\BotPaymentsRepository;
use Illuminate\Http\Request;

class TelegramPaymentController extends Controller
{
    public function index(
        Request $request,
        BotPaymentsRepository $botPaymentsRepository
    )
    {
        $user = auth()->user();
        $data['botPayments'] = $user->isAdmin()
            ? $botPaymentsRepository->getPayments($request->all(), $request->input('page', 1))
            : $botPaymentsRepository->getPayments($request->all(), $request->input('page', 1), $user->id);

        $data['paymentPoints'] = PaymentPoint::query()->get()->keyBy('id');
        $data['users'] = User::query()->get()->keyBy('id');
        $data['userTypes'] = UserType::query()->get()->keyBy('id');
        $data['paymentMethods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['filters'] = $request->input();
        return view('telegram.payments.index', $data);
    }
}
