<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserPaymentConditionsRequest;
use App\Services\UserPaymentConditions\UserPaymentConditionsData;
use App\Services\UserPaymentConditions\UserPaymentConditionsStorageService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class UserPaymentConditionsController extends Controller
{
    public function store(
        UserPaymentConditionsRequest $request,
        UserPaymentConditionsStorageService $userPaymentConditionsStorageService,
    ): RedirectResponse
    {
        try {
            $userPaymentConditionsStorageService->addPaymentConditions(new UserPaymentConditionsData(
                $request->validated(),
            ));
        } catch (Throwable $e)
        {
            return redirect()->route('users.index')->withErrors(['user-payment-condition' => $e->getMessage()]);
        }
        return redirect()->route('users.index');
    }

    public function update(
        UserPaymentConditionsRequest $request,
        UserPaymentConditionsStorageService $userPaymentConditionsStorageService,
    )
    {
        try {
            $userPaymentConditionsStorageService->updatePaymentConditions(new UserPaymentConditionsData(
                $request->validated(),
            ));
        } catch (Throwable $e)
        {
            return redirect()->route('users.index')->withErrors(['user-payment-condition' => $e->getMessage()]);
        }
        return redirect()->route('users.index');
    }
}
