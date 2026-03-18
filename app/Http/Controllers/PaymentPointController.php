<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentPointRequest;
use App\Http\Requests\PaymentPointUpdateRequest;
use App\Models\PaymentPoint;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("payment-points.index", [
            'paymentPoints' => PaymentPoint::query()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("payment-points.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentPointRequest $request)
    {
        PaymentPoint::query()->create($request->validated());
        return redirect()->route('payment-points.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentPointUpdateRequest $request, PaymentPoint $payment_point): RedirectResponse
    {
        $validated = $request->validated();
        $payment_point->name = $validated['payment-points'][$payment_point->id]['name'];
        if(!empty($validated['payment-points'][$payment_point->id]['yandex_token'])) {
            $payment_point->yandex_token = $validated['payment-points'][$payment_point->id]['yandex_token'];
        }
        $payment_point->payment_purpose = $validated['payment-points'][$payment_point->id]['payment_purpose'];
        $payment_point->merchant_id = $validated['payment-points'][$payment_point->id]['merchant_id'];
        $payment_point->account_id = $validated['payment-points'][$payment_point->id]['account_id'];
        $payment_point->customer_code = $validated['payment-points'][$payment_point->id]['customer_code'];

        $payment_point->save();
        return back();
    }

    public function delete(Request $request): JsonResponse
    {
        $users = User::query()->where('payment_point_id', $request->post('id'))->get();
        foreach ($users as $user)
        {
            $user->payment_point_id = 1;
            $user->save();
        }

        PaymentPoint::query()->where('id', $request->post('id'))->delete();
        return response()->json('OK');
    }
}
