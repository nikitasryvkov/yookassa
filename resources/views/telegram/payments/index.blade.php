<x-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="section-title">Платежи через бота</h1>
    </div>

    <div class="glass-card mb-6">
        <bot-payments-search
            :user-types="{{ json_encode($userTypes) }}"
            :users="{{ json_encode($users) }}"
            :payment-points="{{ json_encode($paymentPoints) }}"
            :payment-methods="{{ json_encode($paymentMethods) }}"
            :filters="{{ json_encode($filters) }}"
            :is-admin="{{ json_encode(auth()->user()->isAdmin()) }}">
        </bot-payments-search>
    </div>

    {{ $botPayments->links() }}

    @include('partials.bot-payments')

    <div class="mt-4">
        {{ $botPayments->links() }}
    </div>
</x-layout>
