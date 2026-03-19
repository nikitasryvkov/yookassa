@php use Illuminate\Support\Carbon; @endphp
<x-layout>
    {{-- Dashboard header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-50">Дэшборд</h1>
        <p class="text-sm text-slate-400">Обзор платежей и баланса</p>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
            <span class="stat-label">Баланс счёта</span>
            <account-balance :account-id='@json($accountId)'>
                <template #commission>
                    <span class="stat-sub">Комиссия к выплате: <strong class="text-emerald-400">{{ $commissionSum }}</strong></span>
                </template>
            </account-balance>
        </div>

        <div class="stat-card">
            <span class="stat-label">Сегодняшние поступления</span>
            <span class="stat-value">{{ count($payments ?? []) + count($botPayments ?? []) }}</span>
            <span class="stat-sub">
                СБП: {{ count($payments ?? []) }} &middot; Бот: {{ count($botPayments ?? []) }}
            </span>
        </div>

        <div class="stat-card">
            <span class="stat-label">Дата</span>
            <span class="stat-value text-lg">{{ now()->format('d.m.Y') }}</span>
            <span class="stat-sub">{{ now()->format('H:i') }}</span>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <welcome-page now="{{now()->format('d-m-Y H:i')}}" :payments="{{ $payments }}">
            @if(!empty($payments))
                <div class="section-title mb-4">Сегодняшние поступления</div>
            @endif

            <template #cards>
                @foreach($payments as $payment)
                    <x-card class="mb-3 payment-card" data-type="{{ $payment->type }}" data-id="{{ $payment->id }}">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="tochka-bank-payment-name-{{$payment->type}}">{{ $types[$payment->type] }}</span>
                        </div>
                        <div class="font-semibold text-slate-100">{{ $payment->name }}</div>
                        <div class="text-sm text-slate-400">{{ $payment->purpose }}</div>
                        <div class="text-lg font-bold text-emerald-400 mt-1">{{ $payment->amount }} ₽</div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{Carbon::parse($payment->created_at)->format('d-m-Y H:i')}}
                        </div>
                        <div class="mt-2 border-t border-slate-700/40 pt-2">
                            @empty($payment->receipt)
                                <label for="check-create-{{$payment->type}}-{{$payment->id}}" class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                                    <input id="check-create-{{$payment->type}}-{{$payment->id}}" class="create-reciept h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500" type="checkbox">
                                    Создать чек
                                </label>
                            @endempty
                            <div class="@empty($payment->receipt) invisible @endif check-status text-sm text-emerald-400 font-medium">
                                Запрос на создание чека отправлен
                                <span class="text-slate-400">{{ isset($payment->receipt->checkout_date_time) ? Carbon::parse($payment->receipt->checkout_date_time)->format('d-m-Y H:i') : '' }}</span>
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
            </template>

            <template #bpayments>
                @include('partials.bot-payments')
            </template>

        </welcome-page>
    @else
        @if(!empty($botPayments))
            <div class="section-title mb-4">Сегодняшние платежи</div>
        @endif
        @include('partials.bot-payments')
    @endif
</x-layout>
