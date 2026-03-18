@php use Illuminate\Support\Carbon; @endphp
@foreach($botPayments as $payment)
    <x-card class="mb-3">
        <div>Точка: <span class="font-semibold">{{ $paymentPoints[$payment->payment_point_id]->name ?? 'Нет данных' }}</span></div>
        <div>Пользователь: <span class="font-semibold">{{ $users[$payment->user_id]->name ?? 'Нет данных' }}</span></div>
        <div>Тип пользователя: {{ $userTypes[$payment->user_type_id]->name ?? 'Нет данных' }}</div>
        <div>Способ оплаты: {{ $paymentMethods[$payment->payment_method_id]->name ?? 'Нет данных' }}</div>
        <div>Сумма: {{ $payment->sum }}</div>
        <div>Комиссия: {{ $payment->commission }}</div>
        <div>Всего: {{ $payment->total }}</div>
        <div>Агентские к начислению: {{ $payment->agent_commission_sum }}</div>
        <div>
            Плательщик: telegram id {{ $payment->telegram_id }}, тэг {{ $payment->payer_tag ?? '' }}
        </div>
        <div>Дата: {{ Carbon::parse($payment->created_at)->format('H:i d-m-Y') }}</div>
    </x-card>
@endforeach
