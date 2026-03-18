<?php

namespace App\Repositories;

use App\Models\BotPayments;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BotPaymentsRepository
{
    public function getTodayPayments(): Collection
    {
        return BotPayments::query()->where('created_at', '>', today()->format('Y-m-d'))
            ->where('created_at', '<=' , today()->format('Y-m-d 23:59:59'))
            ->orderBy('created_at', 'DESC')->get();
    }
    public function getMyTodayPayments(int $userId): Collection
    {
        return BotPayments::query()->where('user_id', $userId)
            ->where('created_at', '>', today()->format('Y-m-d'))
            ->where('created_at', '<=' , today()->format('Y-m-d 23:59:59'))
            ->orderBy('created_at', 'DESC')->get();
    }

    public function getPayments(array $filter, $page, int $userId = 0): LengthAwarePaginator
    {
        $query = BotPayments::query();

        //не админ
        if($userId) {
            $query->where('user_id', $userId);
        }

        // Применяем фильтрацию по каждому полю
        if (!empty($filter['id'])) {
            $query->where('id', $filter['id']);
        }

        if (!$userId && !empty($filter['user_id'])) {
            $query->where('user_id', $filter['user_id']);
        }

        if (!empty($filter['telegram_id'])) {
            $query->where('telegram_id', 'like', "%{$filter['telegram_id']}%");
        }

        if (!empty($filter['user_type_id'])) {
            $query->where('user_type_id', $filter['user_type_id']);
        }

        if (!empty($filter['total'])) {
            $query->where('total', $filter['total']);
        }

        if (!empty($filter['sum'])) {
            $query->where('sum', $filter['sum']);
        }

        if (!empty($filter['commission'])) {
            $query->where('commission', $filter['commission']);
        }

        if (!empty($filter['payment_point_id'])) {
            $query->where('payment_point_id', $filter['payment_point_id']);
        }

        if (!empty($filter['payment_method_id'])) {
            $query->where('payment_method_id', $filter['payment_method_id']);
        }

        if (!empty($filter['payer_tag'])) {
            $query->where('payer_tag', 'like', "%{$filter['payer_tag']}%");
        }

        if (!empty($filter['created_at'])) {
            $query->where('created_at', '>=' , $filter['created_at'] . ' 0:00)');
            $query->where('created_at', '<=' , $filter['created_at'] . ' 23:59)');
        }

        // Пагинация: передаем количество элементов на странице и текущую страницу
        return $query->orderBy('created_at', 'DESC')
            ->paginate(20, ['*'], 'page', $page)
            ->appends($filter);
    }

}
