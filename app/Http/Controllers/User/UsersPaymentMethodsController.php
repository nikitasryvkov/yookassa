<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersPaymentMethodsController extends Controller
{
    public function createOrDelete(
        Request $request
    ): JsonResponse
    {
        $post = $request->post();
        $user = User::query()->where('id', $post['user'])->with('userPaymentMethods')->first();
        if(empty($user)) {
            return response()->json('Пользователь ' . $post['user'] . ' не найден');
        }

        if($post['checked'] === 1 &&
            $user->userPaymentMethods->where('payment_method_id', $post['method'])->isEmpty()
        ) {
            $user->userPaymentMethods()->create([
                'payment_method_id' => $post['method'],
            ]);
            return response()->json('Метод оплаты добавлен');
        }

        if($post['checked'] === 0) {
            UserPaymentMethod::query()->where('payment_method_id', $post['method'])->delete();
            return response()->json('Метод оплаты удалён');
        }

        return response()->json('Ничего не произошло');
    }
}
