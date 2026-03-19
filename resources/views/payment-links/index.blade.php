@php use Carbon\Carbon; @endphp
<x-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="section-title">Ссылки на оплату</h1>
            <p class="section-subtitle">Создание и управление платёжными ссылками</p>
        </div>
    </div>

    @if(!empty($user['payment_point']) && !empty($user['user_payment_methods']))
        <div class="glass-card mb-8">
            <h2 class="text-sm font-semibold text-slate-200 mb-4">Создать ссылку</h2>
            <form action="{{ route('url-payments.create') }}" method="POST" class="space-y-4">
                @csrf
                <payment-url-form
                    :methods='@json($user["user_payment_methods"])'
                    :yandex-commission='@json($user["yandex_commission_rate"])'
                    :card-commission='@json($user["card_commission_rate"])'
                    :yookassa-commission='@json($user["yookassa_commission_rate"] ?? $user["card_commission_rate"])'
                    :qr-commission='@json($user["qr_commission_rate"])'>
                </payment-url-form>

                <button type="submit" class="btn-success w-full sm:w-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Создать
                </button>
                <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                <input type="hidden" name="user_type_id" value="{{ $user['type_id'] }}">
                <input type="hidden" name="payment_point_id" value="{{ $user['payment_point_id'] }}">
            </form>
        </div>
    @endif

    {{ $urlPayments->links() }}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @foreach($urlPayments as $payment)
            <div class="glass-card animate-slide-up group">
                <div class="flex items-center justify-between mb-3">
                    <span class="badge-info">{{ $methods[$payment->payment_method_id]['name'] }}</span>
                    @if($payment->payed)
                        <span class="badge-success">Оплачена</span>
                    @else
                        <span class="badge-warning">Ожидает</span>
                    @endif
                </div>

                {{-- Link with copy --}}
                <div class="flex items-center gap-2 mb-3">
                    <input type="text" value="{{ $payment->url }}" readonly
                        class="form-input text-xs truncate" onfocus="this.select()">
                    <button class="btn-ghost shrink-0 p-2" title="Копировать"
                        onclick="navigator.clipboard.writeText(this.previousElementSibling.value)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Создал</span>
                        <span class="text-slate-200">{{ $users[$payment->user_id]->email ?? 'удалён' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Сумма</span>
                        <span class="text-slate-200 font-medium">{{ $payment->sum }} ₽</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Комиссия</span>
                        <span class="text-slate-200">{{ $payment->commission }} ₽</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">К оплате</span>
                        <span class="text-lg font-bold text-slate-50">{{ $payment->total }} ₽</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Агентские</span>
                        <span class="text-emerald-400 font-medium">{{ $payment->agent_commission_sum }} ₽</span>
                    </div>
                </div>

                <div class="mt-3 border-t border-slate-700/40 pt-2 text-xs text-slate-500 space-y-0.5">
                    <div>Создана: {{ Carbon::parse($payment->created_at)->format("d.m.Y H:i") }}</div>
                    @if($payment->payed)
                        <div class="text-emerald-400">Оплачена: {{ Carbon::parse($payment->payed)->format("d.m.Y H:i") }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $urlPayments->links() }}
    </div>
</x-layout>
