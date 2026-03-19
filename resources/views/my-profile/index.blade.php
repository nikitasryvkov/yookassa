<x-layout>
    <div class="mb-6">
        <h1 class="section-title">Профиль</h1>
        <p class="section-subtitle">Реквизиты для получения выплат</p>
    </div>

    <div class="glass-card max-w-3xl">
        <x-validation-errors />

        <form action="{{ isset($userAccount->id) ? route('profile-account.update') : route('profile-account.store') }}"
              method="POST" autocomplete="off" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="counterparty-name" class="form-label">Получатель платежа</label>
                    <input type="text" id="counterparty-name" name="counterparty_name" placeholder="Получатель платежа"
                        value="{{ old('counterparty_name', $userAccount->counterparty_name ?? '') }}"
                        class="form-input" />
                </div>

                <div>
                    <label for="account-number" class="form-label">Счёт (20 симв.)</label>
                    <input type="text" id="account-number" name="account_number" placeholder="Счёт получателя"
                        value="{{ old('account_number', $userAccount->account_number ?? '') }}"
                        class="form-input" />
                </div>

                <div>
                    <label for="payment-purpose" class="form-label">Назначение платежа</label>
                    <input type="text" id="payment-purpose" name="payment_purpose" placeholder="Назначение платежа"
                        value="{{ old('payment_purpose', $userAccount->payment_purpose ?? '') }}"
                        class="form-input" />
                </div>

                <div>
                    <label for="bic" class="form-label">БИК получателя</label>
                    <input type="text" id="bic" name="bic" placeholder="БИК получателя"
                        value="{{ old('bic', $userAccount->bic ?? '') }}"
                        class="form-input" />
                </div>
            </div>

            <div>
                <x-label for="details">Детали</x-label>
                <x-text-input name="details" type="textarea" value="{{ $userAccount->details ?? '' }}" formRef="profileAccountForm"/>
            </div>

            <input type="hidden" name="id" value="{{ $userAccount->id ?? '' }}">

            <button type="submit" class="btn-success">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                </svg>
                Сохранить
            </button>
        </form>
    </div>
</x-layout>
