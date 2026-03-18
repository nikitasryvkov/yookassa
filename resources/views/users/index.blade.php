<x-layout>
    @if($errors->hasAny(['user-payment-condition']))
        Ошибки сохранения условий выплаты:
        <x-validation-errors></x-validation-errors>
    @endif
    @foreach($users as $user)
        <div class="p-6 mb-2 rounded-lg shadow-md bg-sky-100">
            <form action="{{ route('users.update', [$user]) }}" autocomplete="off" method="POST">
                @csrf
                @method('PUT')
                <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-center gap-4 text-sm">
                    @include('users.partials.user-form')
                    @if ($errors->hasAny(['users.' . $user->id . '.name', 'users.' . $user->id . '.password',
                        'users.' . $user->id . '.email', 'users.' . $user->id . '.telegram_id', 'users.' . $user->id . '.role_id',
                        'users.' . $user->id . '.type_id', 'users.' . $user->id . '.payment_point_id', 'users.' . $user->id . '.qr_commission_rate',
                        'users.' . $user->id . '.card_commission_rate', 'users.' . $user->id . '.yookassa_commission_rate', 'users.' . $user->id . '.yandex_commission_rate',
                        'users.' . $user->id . '.agent_commission_rate', 'users.' . $user->id . 'bic',
                        'users.' . $user->id . 'payment_purpose', 'users.' . $user->id . 'counterparty_name',
                        'users.' . $user->id . 'account_number']))
                        <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded w-full">
                            <h4 class="font-bold">Ошибки:</h4>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->get('users.' . $user->id . '.*') as $fieldErrors)
                                    @foreach ($fieldErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="flex gap-2">
                        <div class="flex flex-col">
                            <label class="hidden md:block">&nbsp;</label>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-600
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                &check;
                            </button>
                        </div>
                        <div class="flex flex-col">
                            <delete-user-btn :id="{{ json_encode($user->id) }}"></delete-user-btn>
                        </div>
                    </div>

                    <div class="flex gap-2 w-full">
                        <label for="freelancer-{{ $user->id }}">
                            <input
                                data-user="{{ $user->id }}"
                                type="checkbox"
                                id="freelancer-{{ $user->id }}"
                                name="freelancer"
                                @checked($user->freelancer)
                                onchange="
                                    axios.post('users/freelancer', {
                                        id: this.dataset.user,
                                        freelancer: this.checked ? 'on' : null
                                    }).then(() => console.log('Обновлено'));
                                "
                            >
                             Самозанятый
                        </label>
                    </div>

                    <div class="flex gap-2 w-full">
                        <user-payment-types>
                            @foreach($paymentMethods as $id => $method)
                                <label for="method-{{ $user->id }}-{{ $id }}">
                                    <input
                                        data-user="{{ $user->id }}"
                                        data-method="{{ $id }}"
                                        type="checkbox"
                                        id="method-{{ $user->id }}-{{ $id }}"
                                        name="payment_method_id"
                                        @checked($user->userPaymentMethods->where('payment_method_id', $id)->isNotEmpty())
                                    >
                                    {{ $method->name }}
                                </label>
                            @endforeach
                        </user-payment-types>
                    </div>
                </div>
            </form>

            <div class="border-gray-300 text-sm mt-2">

                <toggle-block :initial-visible="@json($errors->hasAny(['rate-' . $user->id, 'commission-' . $user->id, 'limit-' . $user->id]))">
                    <template #button>
                        <x-button variant="primary" @class(['my-2'])>Установка комиссий и реквизиты для вывода стредств:</x-button><br>
                    </template>

                    <!-- Это то, что скрывается/показывается -->
                    <div>
                        @include('users.partials.user-payments-conditions-form')

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-2">
                            <div>Получатель платежа: {{ $user->accountDetails->counterparty_name ?? ''}}</div>
                            <div>Счёт: {{ $user->accountDetails->account_number ?? '' }}</div>
                            <div>Назначение платежа: {{ $user->accountDetails->payment_purpose ?? '' }}</div>
                            <div>БИК получателя: {{ $user->accountDetails->bic ?? '' }}</div>
                        </div>

                        <div class="w-full">
                            Детали: {{ $user->accountDetails->details ?? '' }}
                        </div>
                    </div>
                </toggle-block>
            </div>
        </div>
    @endforeach
</x-layout>
