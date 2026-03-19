@php use Illuminate\Support\Carbon; @endphp
<x-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="section-title">Интернет-эквайринг</h1>
    </div>

    {{ $payments->links() }}

    <internet-payments>
        <template #cards>
            <div class="space-y-3 mt-4">
                @foreach($payments as $payment)
                    <x-card class="payment-card" data-type="{{ $payment->type }}" data-id="{{ $payment->id }}">
                        <div class="flex flex-wrap justify-between gap-4">
                            <div class="space-y-1 text-sm min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-100">{{ $payment->payer_name }}</span>
                                    <span class="badge-neutral">{{ $payment->payment_type }}</span>
                                </div>
                                <div class="text-xs text-slate-500">Назначение: {{ $payment->purpose }}</div>
                                <div class="text-xs text-slate-500">Клиент: {{ $payment->customer_code }}</div>
                                <div class="text-xs text-slate-600">Транзакция: {{ $payment->transaction_id }} &middot; Op: {{ $payment->operation_id }}</div>
                                <div class="text-xs text-slate-600">QR: {{ $payment->qrc_id }}</div>
                                <div class="text-xs text-slate-500">{{ Carbon::parse($payment->created_at)->format('d.m.Y H:i') }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-lg font-bold text-slate-50">{{ $payment->amount }} ₽</div>
                            </div>
                        </div>

                        <div class="mt-3 border-t border-slate-700/40 pt-2 text-sm">
                            @empty($payment->receipt)
                                <label for="check-create-{{$payment->type}}-{{$payment->id}}" class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                    <input id="check-create-{{$payment->type}}-{{$payment->id}}" class="create-reciept h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500" type="checkbox">
                                    Создать чек
                                </label>
                            @endempty
                            <div class="@empty($payment->receipt) invisible @endif check-status text-emerald-400 font-medium text-sm">
                                Чек создан
                                <span class="text-slate-400">{{ isset($payment->receipt->checkout_date_time) ? Carbon::parse($payment->receipt->checkout_date_time)->format('d.m.Y H:i') : '' }}</span>
                            </div>
                            @isset($payment->receipt->statuses)
                                @foreach($payment->receipt->statuses as $status)
                                    <div class="text-xs text-slate-400">Статус: <span class="badge-info">{{$status['status']}}</span> ФН: {{ $status['fn_state'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $status['message'] }}</div>
                                    @if($status['failure_info'])
                                        <div class="text-xs text-red-400">{{ $status['failure_info'] }}</div>
                                    @endif
                                @endforeach
                            @endisset
                            @isset($payment->receipt)
                                <div class="check-receipt-status-container mt-1">
                                    <a href="#" data-id="{{ $payment->receipt->id }}"
                                       class="check-receipt-status text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                        Проверить статус чека
                                    </a>
                                    <div class="check-receipt-status-response text-xs text-slate-400 mt-1"></div>
                                </div>
                            @endisset
                        </div>
                    </x-card>
                @endforeach
            </div>
        </template>
    </internet-payments>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</x-layout>
