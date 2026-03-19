@php use Illuminate\Support\Carbon; @endphp
<div class="space-y-3">
    @foreach($botPayments as $payment)
        <x-card>
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-slate-100">{{ $users[$payment->user_id]->name ?? 'Нет данных' }}</span>
                        <span class="badge-info">{{ $paymentMethods[$payment->payment_method_id]->name ?? '—' }}</span>
                        <span class="badge-neutral">{{ $userTypes[$payment->user_type_id]->name ?? '—' }}</span>
                    </div>
                    <div class="text-xs text-slate-500">
                        Точка: {{ $paymentPoints[$payment->payment_point_id]->name ?? 'Нет данных' }}
                    </div>
                    <div class="text-xs text-slate-500">
                        Плательщик: tg {{ $payment->telegram_id }}
                        @if($payment->payer_tag), тэг {{ $payment->payer_tag }}@endif
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ Carbon::parse($payment->created_at)->format('H:i d-m-Y') }}
                    </div>
                </div>

                <div class="text-right space-y-0.5 shrink-0">
                    <div class="text-lg font-bold text-slate-100">{{ $payment->total }} ₽</div>
                    <div class="text-xs text-slate-500">Сумма: {{ $payment->sum }} ₽</div>
                    <div class="text-xs text-slate-500">Комиссия: {{ $payment->commission }} ₽</div>
                    <div class="text-xs text-emerald-400">Агентские: {{ $payment->agent_commission_sum }} ₽</div>
                </div>
            </div>
        </x-card>
    @endforeach
</div>
