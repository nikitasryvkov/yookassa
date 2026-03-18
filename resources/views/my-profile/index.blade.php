<x-layout>
    <div class="p-6 rounded-lg shadow-md bg-white space-y-6">
        <x-validation-errors />

        <form action="{{ isset($userAccount->id) ? route('profile-account.update') : route('profile-account.store') }}"
              method="POST"
              autocomplete="off"
              class="space-y-6">
            @csrf

            {{-- Сетка из 2-х полей --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
                {{-- Получатель платежа --}}
                <div class="flex flex-col">
                    <label for="counterparty-name" class="text-sm font-medium text-gray-700 mb-1">
                        Получатель платежа
                    </label>
                    <input type="text" id="counterparty-name" name="counterparty_name" placeholder="Получатель платежа"
                           value="{{ old('counterparty_name', $userAccount->counterparty_name ?? '') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"/>
                </div>

                {{-- Счёт --}}
                <div class="flex flex-col">
                    <label for="account-number" class="text-sm font-medium text-gray-700 mb-1">
                        Счёт (20 симв.)
                    </label>
                    <input type="text" id="account-number" name="account_number" placeholder="Счёт получателя"
                           value="{{ old('account_number', $userAccount->account_number ?? '') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"/>
                </div>

                {{-- Назначение платежа --}}
                <div class="flex flex-col">
                    <label for="payment-purpose" class="text-sm font-medium text-gray-700 mb-1">
                        Назначение платежа
                    </label>
                    <input type="text" id="payment-purpose" name="payment_purpose" placeholder="Назначение платежа"
                           value="{{ old('payment_purpose', $userAccount->payment_purpose ?? '') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"/>
                </div>

                {{-- БИК --}}
                <div class="flex flex-col">
                    <label for="bic" class="text-sm font-medium text-gray-700 mb-1">
                        БИК получателя
                    </label>
                    <input type="text" id="bic" name="bic" placeholder="БИК получателя"
                           value="{{ old('bic', $userAccount->bic ?? '') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"/>
                </div>
            </div>

            {{-- Детали + кнопка --}}
            <div class="space-y-4">
                <div>
                    <x-label for="details">Детали</x-label>
                    <x-text-input name="details" type="textarea" value="{{ $userAccount->details ?? '' }}" formRef="profileAccountForm"/>
                </div>

                <input type="hidden" name="id" value="{{ $userAccount->id ?? '' }}">

                <div>
                    <x-button variant="success">💾 Сохранить</x-button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
