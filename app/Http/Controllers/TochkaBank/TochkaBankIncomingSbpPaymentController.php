<?php

namespace App\Http\Controllers\TochkaBank;

use App\Http\Controllers\Controller;
use App\Models\TochkaBank\TochkaBankIncomingSbpPayment;
use App\Repositories\TochkaBank\TochkaBankPaymentsRepository;
use Illuminate\Http\Request;

class TochkaBankIncomingSbpPaymentController extends Controller
{
    public function index(
        Request $request,
        TochkaBankPaymentsRepository $tochkaBankPaymentsRepository
    )
    {
        $data['payments'] = $tochkaBankPaymentsRepository->getSbpPayments($request->all(), $request->input('page', 1));
        $data['filters'] = $request->input();
        return view('tb.incoming-sbp-payments', $data);
    }
}
