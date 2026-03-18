@php use Carbon\Carbon; @endphp
<x-layout>
    <div class="text-lg font-semibold text-center">Ссылки на оплату</div>
    <br>

    @if(!empty($user['payment_point']) && !empty($user['user_payment_methods']))
        <form action="{{ route('url-payments.create') }}" method="POST" class="w-full p-6 bg-white rounded-2xl shadow-lg space-y-4">
            @csrf
            <payment-url-form
                :methods='@json($user["user_payment_methods"])'
                :yandex-commission='@json($user["yandex_commission_rate"])'
                :card-commission='@json($user["card_commission_rate"])'
                :yookassa-commission='@json($user["yookassa_commission_rate"] ?? $user["card_commission_rate"])'
                :qr-commission='@json($user["qr_commission_rate"])'>
            </payment-url-form>

            <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                <button type="submit"
                        class="w-full sm:w-1/2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Создать
                </button>
            </div>
            <input type="hidden" name="user_id" value="{{ $user['id'] }}">
            <input type="hidden" name="user_type_id" value="{{ $user['type_id'] }}">
            <input type="hidden" name="payment_point_id" value="{{ $user['payment_point_id'] }}">
        </form>
    @endif

    {{ $urlPayments->links() }}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
        @foreach($urlPayments as $payment)
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300 hover:shadow-lg transition-shadow duration-300">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    Способ оплаты: {{ $methods[$payment->payment_method_id]['name'] }}
                </h3>
                <div class="mb-4">
                    <span class="text-gray-600">Ссылка: </span>
                    <div class="flex items-center">
                        <!-- Сделаем ссылку доступной для копирования -->
                        <input
                            type="text"
                            value="{{ $payment->url }}"
                            readonly
                            class="text-blue-600 hover:underline flex-1 border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            onfocus="this.select()"
                        >
                        <button class="ml-2 text-sm text-gray-500 hover:text-gray-700" onclick="this.previousElementSibling.select(); document.execCommand('copy');">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4-4m0 0l4 4m-4-4v12m-4-4v4H5a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H15z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 mb-2">Создал: {{ $users[$payment->user_id]->email ?? 'удалён' }}</p>
                <p class="text-gray-600 mb-2">Сумма: <strong>{{ $payment->sum }} ₽</strong></p>
                <p class="text-gray-600 mb-2">Комиссия: <strong>{{ $payment->commission }} ₽</strong></p>
                <p class="text-gray-600 mb-2">К оплате: <strong>{{ $payment->total }} ₽</strong></p>
                <p class="text-gray-600 mb-2">Агентские: <strong>{{ $payment->agent_commission_sum }} ₽</strong></p>
                <p class="text-gray-600 mb-2">Создана: {{ Carbon::parse($payment->created_at)->format("d-m-Y H:i") }}</p>
                <p class="text-gray-600">Оплачена: @if($payment->payed) {{ Carbon::parse($payment->payed)->format("d-m-Y H:i") }} @endif</p>
            </div>
        @endforeach
    </div>
    {{ $urlPayments->links() }}
</x-layout>
