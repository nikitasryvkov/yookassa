<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\PaymentMethod;
use App\Models\PaymentPoint;
use App\Models\User;
use App\Models\UserPaymentMethod;
use App\Models\UserRole;
use App\Models\UserType;
use App\Services\User\UserStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['users'] = User::query()->with(['userPaymentMethods', 'accountDetails', 'PaymentConditions'])->get();
        $data['roles'] = UserRole::query()->get()->keyBy('id');
        $data['types'] = UserType::query()->get()->keyBy('id');
        $data['paymentMethods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['paymentPoints'] = PaymentPoint::query()->get()->keyBy('id');
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = UserRole::query()->orderBy('name')->get()->keyBy('id');
        $data['types'] = UserType::query()->get()->keyBy('id');
        $data['paymentMethods'] = PaymentMethod::query()->get()->keyBy('id');
        $data['paymentPoints'] = PaymentPoint::query()->get()->keyBy('id');
        return view('users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        UserCreateRequest $request
    ): RedirectResponse
    {
        User::query()->create($request->validated());
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UserUpdateRequest $request,
        User $user
    ): RedirectResponse
    {
        $validated = $request->validated();

        $user->name = $validated['users'][$user->id]['name'];
        if(!empty($validated['users'][$user->id]['password'])) {
            $user->password = $validated['users'][$user->id]['password'];
        }
        $user->email = $validated['users'][$user->id]['email'];
        $user->telegram_id = $validated['users'][$user->id]['telegram_id'];
        $user->role_id = $validated['users'][$user->id]['role_id'];
        $user->type_id = $validated['users'][$user->id]['type_id'];
        $user->payment_point_id = $validated['users'][$user->id]['payment_point_id'];
        $user->qr_commission_rate = $validated['users'][$user->id]['qr_commission_rate'];
        $user->card_commission_rate = $validated['users'][$user->id]['card_commission_rate'];
        $user->yandex_commission_rate = $validated['users'][$user->id]['yandex_commission_rate'];
        $user->agent_commission_rate = $validated['users'][$user->id]['agent_commission_rate'];
        $user->bic = $validated['users'][$user->id]['bic'];
        $user->payment_purpose = $validated['users'][$user->id]['payment_purpose'];
        $user->counterparty_name = $validated['users'][$user->id]['counterparty_name'];
        $user->account_number = $validated['users'][$user->id]['account_number'];
        $user->save();

        $user->setRememberToken(Str::random(60));

        return back();
    }

    public function delete(
        Request $request
    )
    {
        UserPaymentMethod::query()->where('user_id', $request->post('id'))->delete();
        User::query()->where('id', $request->post('id'))->delete();
        return response()->json('OK');
    }

    public function setFreelancerCheckbox(
        Request $request,
        UserStorageService $userStorageService,
    ): JsonResponse
    {
        $userStorageService->setFreelancer($request->post('id'), $request->post('freelancer'));
        return response()->json('OK');
    }
}
