@php
    use App\Enums\PaymentStatus;
    use Illuminate\Support\Carbon;
@endphp

<div class="space-y-3">
    @foreach($commissions as $commission)
        <x-card>
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-slate-100">{{ $users[$commission->user_id]->name ?? 'Нет данных' }}</span>
                        <span class="badge-info">{{ $paymentMethods[$commission->payment_method_id]->name ?? '—' }}</span>
                    </div>
                    <div class="text-xs text-slate-500">
                        Точка: {{ $paymentPoints[$commission->payment_point_id]->name ?? 'Нет данных' }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ Carbon::parse($commission->created_at)->format('H:i d-m-Y') }}
                    </div>
                </div>

                <div class="text-right space-y-0.5 shrink-0">
                    <div class="text-xs text-slate-500">Сумма</div>
                    <div class="text-sm text-slate-200">{{ $commission->sum }} ₽</div>
                    <div class="text-xs text-slate-500">Комиссия: {{ $commission->total - $commission->sum }} ₽</div>
                    <div class="text-xs text-slate-500">Всего: {{ $commission->total }} ₽</div>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 border-t border-slate-700/40 pt-3">
                <div class="flex items-center gap-4 text-sm">
                    <div>
                        <span class="text-slate-500">Агентская:</span>
                        <span class="font-bold text-emerald-400">{{ $commission->agent_commission_sum }} ₽</span>
                        <span class="text-slate-600">({{ $commission->agent_commission_rate }}%)</span>
                    </div>
                </div>
                <div>
                    @if($commission->request_id)
                        <span class="badge-success">{{ PaymentStatus::getNameByNumber($commission->status ?? 0) }}</span>
                    @else
                        <agent-commission-payment-request-btn :id={{$commission->id}}></agent-commission-payment-request-btn>
                    @endif
                </div>
            </div>
        </x-card>
    @endforeach
</div>
