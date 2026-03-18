@php
    use App\Enums\PaymentStatus;
    use Illuminate\Support\Carbon;
@endphp

@foreach($commissions as $commission)
    <x-card class="mb-3">
        <div>Точка: <span
                class="font-semibold">{{ $paymentPoints[$commission->payment_point_id]->name ?? 'Нет данных' }}</span>
        </div>
        <div>Пользователь: <span class="font-semibold">{{ $users[$commission->user_id]->name ?? 'Нет данных' }}</span>
        </div>
        <div>Способ оплаты: {{ $paymentMethods[$commission->payment_method_id]->name ?? 'Нет данных' }}</div>
        <div>Сумма: {{ $commission->sum }}</div>
        <div>Комиссия: {{ $commission->total - $commission->sum }}</div>
        <div>Всего: {{ $commission->total }}</div>
        <div>Агентская комиссия: {{ $commission->agent_commission_rate }}%</div>
        <div>Агентские к начислению: <strong>{{ $commission->agent_commission_sum }}</strong></div>
        <div>
            @if($commission->request_id)
                Статус начисления: <strong>{{ PaymentStatus::getNameByNumber($commission->status ?? 0) }}</strong>
            @else
                <agent-commission-payment-request-btn :id={{$commission->id}}></agent-commission-payment-request-btn>
            @endif
        </div>
        <div>Дата: {{ Carbon::parse($commission->created_at)->format('H:i d-m-Y') }}</div>
    </x-card>
@endforeach
