<x-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="section-title">Агентские отчёты</h1>
            <p class="section-subtitle">Комиссионные начисления и выплаты</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="stat-card">
            <span class="stat-label">Баланс счёта</span>
            <account-balance :account-id='@json($accountId)'>
                <template #commission>
                    <div class="mt-1">
                        <span class="stat-sub">Комиссия к выплате:</span>
                        <span class="text-lg font-bold text-emerald-400">{{ $commissionSum }}</span>
                    </div>
                </template>
            </account-balance>
        </div>
    </div>

    {{-- Search --}}
    <div class="glass-card mb-6">
        <commissions-search
            :users="{{ json_encode($users) }}"
            :payment-points="{{ json_encode($paymentPoints) }}"
            :payment-methods="{{ json_encode($paymentMethods) }}"
            :filters="{{ json_encode($filters) }}"
            :is-admin="{{ json_encode(auth()->user()->isAdmin()) }}">
        </commissions-search>
    </div>

    {{ $commissions->links() }}

    @include('partials.commissions')

    <div class="mt-4">
        {{ $commissions->links() }}
    </div>
</x-layout>
