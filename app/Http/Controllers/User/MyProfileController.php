<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\Users\UserAccountRequest;
use App\Services\UserAccount\UserAccountData;
use App\Services\UserAccount\UserAccountStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class MyProfileController
{
    public function index(
        UserAccountStorageService $userAccountStorageService,
    ): View
    {
        $data['userId'] = Auth::user()->id;
        $data['userAccount'] = $userAccountStorageService->getAccountDetails($data['userId']);

        return view('my-profile.index', $data);
    }

    public function storeUserAccount(
        UserAccountRequest $request,
        UserAccountStorageService $userAccountStorageService,
    ): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::user()->id;

            $userAccountStorageService->addAccountDetails(
                new UserAccountData($validated),
            );
        } catch (Throwable $e)
        {
            return redirect()->route('profile.index')->withInput()->withErrors([$e->getMessage()]);
        }

        return redirect()->route('profile.index');
    }

    public function updateUserAccount(
        UserAccountRequest $request,
        UserAccountStorageService $userAccountStorageService,
    ): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::user()->id;

            $userAccountStorageService->updateAccountDetails(
                new UserAccountData($validated),
            );
        } catch (Throwable $e)
        {
            return redirect()->route('profile.index')->withInput()->withErrors([$e->getMessage()]);
        }

        return redirect()->route('profile.index');
    }
}
