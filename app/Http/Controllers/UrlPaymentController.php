<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentUrlCreateRequest;
use App\Models\PaymentMethod;
use App\Models\UrlPayment;
use App\Models\User;
use App\Services\PaymentUrlCreateService;
use Illuminate\Http\Request;

class UrlPaymentController extends Controller
{
    public function index(
        Request $request,
    )
    {
        $data['user'] = User::query()->where('id', auth()->user()->id)
            ->with(['paymentPoint', 'userPaymentMethods.method'])->first()->toArray();

        $data['urlPayments'] = UrlPayment::query();

        if(!auth()->user()->isAdmin())
        {
            $data['urlPayments'] = $data['urlPayments']->where('user_id', auth()->user()->id);
        }

        $data['urlPayments'] = $data['urlPayments']
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        $data['urlPayments']->appends($request->input());

        $data['methods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['users'] = User::query()->get()->keyBy('id');
        return view('payment-links.index', $data);
    }

    public function create(
        PaymentUrlCreateRequest $request,
        PaymentUrlCreateService $action,
    )
    {
        $validated = $request->validated();
        $validated['user'] = User::query()->where('id', $validated['user_id'])
            ->with( 'paymentPoint')->first()->toArray();

        $action->handle($validated);

        return back();
    }
}
