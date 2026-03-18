<x-layout>
    <div class="text-lg font-semibold text-center">Комиссия</div>
    <br>
    <account-balance :account-id='@json($accountId)'>
        <template #commission>
            <span class="pl-2">Комиссия к выплате: <strong>{{ $commissionSum }}</strong></span>
        </template>
    </account-balance>
    <commissions-search
        :users="{{ json_encode($users) }}"
        :payment-points="{{ json_encode($paymentPoints) }}"
        :payment-methods="{{ json_encode($paymentMethods) }}"
        :filters="{{ json_encode($filters) }}"
        :is-admin="{{ json_encode(auth()->user()->isAdmin()) }}"
    >
    </commissions-search>
    <br>

    {{ $commissions-> links() }}

    @include('partials.commissions')

    {{ $commissions -> links() }}
</x-layout>
