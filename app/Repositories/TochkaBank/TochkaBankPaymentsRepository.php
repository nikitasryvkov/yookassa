<?php

namespace App\Repositories\TochkaBank;

use App\Models\TochkaBank\TochkaBankAcquiringInternetPayment;
use App\Models\TochkaBank\TochkaBankIncomingSbpPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TochkaBankPaymentsRepository
{
    public function getTodayPayments(): Collection
    {
        $acquiringInternetPayments = TochkaBankAcquiringInternetPayment::query()
            ->select('payer_name as name', 'purpose', 'amount', 'type', 'id', 'created_at')
            ->with('receipt.statuses')
            ->where('created_at', '>', today()->format('Y-m-d'))
            ->where('created_at', '<=' , today()->format('Y-m-d 23:59:59'))->get();


        $sbpPayments = TochkaBankIncomingSbpPayment::query()
            ->select('payer_name as name', 'purpose', 'amount', 'type', 'id', 'created_at')
            ->with('receipt.statuses')
            ->where('created_at', '>', today()->format('Y-m-d'))
            ->where('created_at', '<=', today()->format('Y-m-d 23:59:59'))
            ->orderBy('created_at', 'DESC')
            ->get();

        return collect($sbpPayments)->merge(collect($acquiringInternetPayments))->sortByDesc('created_at');
    }

    public function getPaymentTypes(): array
    {
        return [
            2 => 'Tochka Bank Incoming Sbp Payments',
            3 => 'Tochka Bank Acquiring Internet Payments',
        ];
    }

    public function getSbpPayments(array $filter, int $page): LengthAwarePaginator
    {
        $query = TochkaBankIncomingSbpPayment::query();

        // Применяем фильтрацию по каждому полю
        if (!empty($filter['operation_id'])) {
            $query->where('operation_id', $filter['operation_id']);
        }

        if (!empty($filter['qrc_id'])) {
            $query->where('qrc_id', $filter['qrc_id']);
        }

        if (!empty($filter['amount'])) {
            $query->where('amount', $filter['amount']);
        }

        if (!empty($filter['payer_mobile_number'])) {
            $query->where('payer_mobile_number', 'like', "%{$filter['payer_mobile_number']}%");
        }

        if (!empty($filter['payer_name'])) {
            $query->where('payer_name', 'like', "%{$filter['payer_name']}%");
        }

        if (!empty($filter['brand_name'])) {
            $query->where('brand_name', 'like', "%{$filter['brand_name']}%");
        }

        if (!empty($filter['merchant_id'])) {
            $query->where('merchant_id', $filter['merchant_id']);
        }

        if (!empty($filter['purpose'])) {
            $query->where('purpose', 'like', "%{$filter['purpose']}%");
        }

        if (!empty($filter['customer_code'])) {
            $query->where('customer_code', $filter['customer_code']);
        }

        if (!empty($filter['created_at'])) {
            $query->where('created_at', '>=' , $filter['created_at'] . ' 0:00)');
            $query->where('created_at', '<=' , $filter['created_at'] . ' 23:59)');
        }

        // Пагинация: передаем количество элементов на странице и текущую страницу
        return $query->orderBy('created_at', 'DESC')
            ->paginate(20, ['*'], 'page', $page)->appends($filter);
    }
}
