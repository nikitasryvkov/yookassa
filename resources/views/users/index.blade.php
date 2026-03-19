<x-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="section-title">Пользователи</h1>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Добавить
        </a>
    </div>

    @if($errors->hasAny(['user-payment-condition']))
        <div class="alert-error mb-4">
            <p class="font-semibold">Ошибки сохранения условий выплаты:</p>
            <x-validation-errors></x-validation-errors>
        </div>
    @endif

    <div class="space-y-4">
        @foreach($users as $user)
            <div class="glass-card animate-slide-up">
                <form action="{{ route('users.update', [$user]) }}" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-end gap-4 text-sm">
                        @include('users.partials.user-form')

                        @if ($errors->hasAny(['users.' . $user->id . '.name', 'users.' . $user->id . '.password',
                            'users.' . $user->id . '.email', 'users.' . $user->id . '.telegram_id', 'users.' . $user->id . '.role_id',
                            'users.' . $user->id . '.type_id', 'users.' . $user->id . '.payment_point_id', 'users.' . $user->id . '.qr_commission_rate',
                            'users.' . $user->id . '.card_commission_rate', 'users.' . $user->id . '.yookassa_commission_rate', 'users.' . $user->id . '.yandex_commission_rate',
                            'users.' . $user->id . '.agent_commission_rate', 'users.' . $user->id . 'bic',
                            'users.' . $user->id . 'payment_purpose', 'users.' . $user->id . 'counterparty_name',
                            'users.' . $user->id . 'account_number']))
                            <div class="alert-error w-full">
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
                                <button type="submit" class="btn-success">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex flex-col">
                                <delete-user-btn :id="{{ json_encode($user->id) }}"></delete-user-btn>
                            </div>
                        </div>
                    </div>

                    {{-- Freelancer toggle --}}
                    <div class="mt-3 flex items-center gap-2">
                        <label for="freelancer-{{ $user->id }}" class="flex items-center gap-2 cursor-pointer text-sm text-slate-300">
                            <input data-user="{{ $user->id }}" type="checkbox" id="freelancer-{{ $user->id }}"
                                name="freelancer" @checked($user->freelancer)
                                class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/30"
                                onchange="axios.post('users/freelancer', { id: this.dataset.user, freelancer: this.checked ? 'on' : null }).then(() => console.log('Обновлено'));">
                            Самозанятый
                        </label>
                    </div>

                    {{-- Payment methods --}}
                    <div class="mt-2 flex flex-wrap gap-3 text-sm text-slate-300">
                        <user-payment-types>
                            @foreach($paymentMethods as $id => $method)
                                <label for="method-{{ $user->id }}-{{ $id }}" class="flex items-center gap-1.5 cursor-pointer">
                                    <input data-user="{{ $user->id }}" data-method="{{ $id }}" type="checkbox"
                                        id="method-{{ $user->id }}-{{ $id }}" name="payment_method_id"
                                        class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/30"
                                        @checked($user->userPaymentMethods->where('payment_method_id', $id)->isNotEmpty())>
                                    {{ $method->name }}
                                </label>
                            @endforeach
                        </user-payment-types>
                    </div>
                </form>

                {{-- Expandable section --}}
                <div class="border-t border-slate-700/40 mt-4 pt-3 text-sm">
                    <toggle-block :initial-visible="@json($errors->hasAny(['rate-' . $user->id, 'commission-' . $user->id, 'limit-' . $user->id]))">
                        <template #button>
                            <button type="button" class="btn-ghost text-xs">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                </svg>
                                Комиссии и реквизиты для вывода
                            </button>
                        </template>

                        <div class="mt-3 space-y-3">
                            @include('users.partials.user-payments-conditions-form')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-400 text-xs">
                                <div>Получатель: <span class="text-slate-200">{{ $user->accountDetails->counterparty_name ?? '—'}}</span></div>
                                <div>Счёт: <span class="text-slate-200">{{ $user->accountDetails->account_number ?? '—' }}</span></div>
                                <div>Назначение: <span class="text-slate-200">{{ $user->accountDetails->payment_purpose ?? '—' }}</span></div>
                                <div>БИК: <span class="text-slate-200">{{ $user->accountDetails->bic ?? '—' }}</span></div>
                            </div>

                            @if($user->accountDetails?->details)
                                <div class="text-xs text-slate-400">
                                    Детали: <span class="text-slate-200">{{ $user->accountDetails->details }}</span>
                                </div>
                            @endif
                        </div>
                    </toggle-block>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
