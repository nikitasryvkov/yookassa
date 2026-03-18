<x-layout>

    <div class="text-lg font-semibold text-center">Платежи через бота</div>
    <br>

    <bot-payments-search
        :user-types="{{ json_encode($userTypes) }}"
        :users="{{ json_encode($users) }}"
        :payment-points="{{ json_encode($paymentPoints) }}"
        :payment-methods="{{ json_encode($paymentMethods) }}"
        :filters="{{ json_encode($filters) }}"
        :is-admin="{{ json_encode(auth()->user()->isAdmin()) }}"
    >
    </bot-payments-search>
    <br>

    {{ $botPayments -> links() }}

    @include('partials.bot-payments')

    {{ $botPayments -> links() }}

</x-layout>
