<?php

namespace App\Repositories;

use App\Models\AgentCommission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AgentCommissionsRepository
{
    public function getCommissions(array $filter, $page, int $userId = 0): LengthAwarePaginator
    {
        $query = AgentCommission::query()->where('agent_commission_rate', '>', 0);

        //не админ
        if($userId) {
            $query->where('user_id', $userId);
        }

        if (!$userId && !empty($filter['user_id'])) {
            $query->where('user_id', $filter['user_id']);
        }

        if (!empty($filter['payment_point_id'])) {
            $query->where('payment_point_id', $filter['payment_point_id']);
        }

        if (!empty($filter['payment_method_id'])) {
            $query->where('payment_method_id', $filter['payment_method_id']);
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

    public function getById(int $id): ?AgentCommission
    {
        return AgentCommission::query()->with(['user', 'paymentPoint'])->where('id', $id)->first();
    }

    public function getCommissionToPay(int $userId): float
    {
        $commissions = AgentCommission::query()->whereNotNull('request_id')->where('user_id', $userId)
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereNotIn('status', [3, 8, 9, 10]);
            })->get();

        if($commissions->isEmpty()) {
            return 0.0;
        }

        $commissionAmount = 0;
        foreach ($commissions as $commission) {
            $commissionAmount += $commission->agent_commission_sum;
        }

        return $commissionAmount;
    }

    public function getCommissionsToCheck(): Collection
    {
        return AgentCommission::query()->whereNotNull('request_id')
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereNotIn('status', [3, 8, 9, 10]);
            })
            ->where('request_date', '>', now()->subHours(48))->get();
    }
}
