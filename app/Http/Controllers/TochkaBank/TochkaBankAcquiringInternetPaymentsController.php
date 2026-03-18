<?php

namespace App\Http\Controllers\TochkaBank;

use App\Http\Controllers\Controller;
use App\Models\TochkaBank\TochkaBankAcquiringInternetPayment;

class TochkaBankAcquiringInternetPaymentsController extends Controller
{
    public function index(
    )
    {
        $data['payments'] = TochkaBankAcquiringInternetPayment::query()
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return view('tb.acquiring-internet-payments', $data);
    }
}
