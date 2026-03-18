<div class="flex flex-col w-full sm:w-1/3">
    <label for="name-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Имя
    </label>
    <input
        type="text"
        id="name-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][name]"
        @else
            name="name"
        @endif
        placeholder="Имя"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('name') ?? $user->name ?? '' }}"
    />

</div>
<div class="flex flex-col w-full sm:w-1/3">
    <label for="email-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Email
    </label>
    <input
        type="text"
        id="email-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][email]"
        @else
            name="email"
        @endif
        placeholder="Почта"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('email') ?? $user->email ?? '' }}"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="password-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Новый пароль
    </label>
    <div class="relative">
        <input
            data-psw="{{ Str::password(8) }}"
            type="text"
            id="password-{{ $user->id ?? '' }}"
            @isset($user->id)
                name="users[{{ $user->id }}][password]"
            @else
                name="password"
            @endif
            placeholder="Новый пароль"
            class="pl-4 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                focus:ring-blue-500 focus:border-blue-500"
            @empty($user)
                value="{{ old('password') ?? Str::password(8) }}"
            @endempty
        />
        @isset($user)
            <button
                title="Предложить пароль"
                type="button"
                class="absolute right-0 top-0 h-full px-3 text-white bg-blue-500 rounded-r-lg hover:bg-blue-600
                focus:outline-none focus:ring-2 focus:ring-blue-500"
                onclick="const input = document.getElementById('password-{{$user->id}}'); input.value = input.dataset.psw"
            >
                psw
            </button>
        @endisset
    </div>
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="telegram-id-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Telegram id
    </label>
    <input
        type="text"
        id="telegram-id-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][telegram_id]"
        @else
            name="telegram_id"
        @endif
        placeholder="Telegram id"
        value="{{ old('telegram_id') ?? $user->telegram_id ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="counterparty-name-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Получатель платежа
    </label>
    <input
        type="text"
        id="counterparty-name-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][counterparty_name]"
        @else
            name="counterparty_name"
        @endif
        placeholder="Получатель платежа"
        value="{{ old('counterparty_name') ?? $user->counterparty_name ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="account-number-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Счёт (20 симв.)
    </label>
    <input
        type="text"
        id="account-number-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][account_number]"
        @else
            name="account_number"
        @endif
        placeholder="Счёт получателя"
        value="{{ old('account_number') ?? $user->account_number ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="payment-purpose-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Назначение платежа
    </label>
    <input
        type="text"
        id="payment-purpose-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][payment_purpose]"
        @else
            name="payment_purpose"
        @endif
        placeholder="Назначение платежа"
        value="{{ old('payment_purpose') ?? $user->payment_purpose ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="bic-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        БИК получателя
    </label>
    <input
        type="text"
        id="bic-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][bic]"
        @else
            name="bic"
        @endif
        placeholder="Бик получателя"
        value="{{ old('bic') ?? $user->bic ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="role-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Роль
    </label>
    <select
        @isset($user->id)
            name="users[{{ $user->id }}][role_id]"
        @else
            name="role_id"
        @endif
        id="role-{{ $user->id ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @foreach($roles as $id => $role)
            <option @if(isset($user) && $user->role_id === $id || old('role_id') == $id) selected @endif value="{{ $id }}">{{ $role['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="type-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Тип
    </label>
    <select
        @isset($user->id)
            name="users[{{ $user->id }}][type_id]"
        @else
            name="type_id"
        @endif
        id="type-{{ $user->id ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @foreach($types as $id => $type)
            <option @if(isset($user) && $user->type_id === $id || old('type_id') == $id ) selected @endif value="{{ $id }}">{{ $type['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="payment_point_id-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Точка продаж
    </label>
    <select
        @isset($user->id)
            name="users[{{ $user->id }}][payment_point_id]"
        @else
            name="payment_point_id"
        @endif
        id="payment_point_id-{{ $user->id ?? '' }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Не выбрана</option>
        @foreach($paymentPoints as $id => $point)
            <option @if(isset($user) && $user->payment_point_id === $id || old('payment_point_id') == $id ) selected @endif value="{{ $id }}">{{ $point['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="qr-commission-rate-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Комиссия QR
    </label>
    <input
        type="text"
        id="qr-commission-rate-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][qr_commission_rate]"
        @else
            name="qr_commission_rate"
        @endif
        placeholder="Комиссия QR"
        value="{{ old('qr_commission_rate') ?? $user->qr_commission_rate ?? 0 }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="card-commission-rate-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Комиссия Card
    </label>
    <input
        type="text"
        id="card-commission-rate-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][card_commission_rate]"
        @else
            name="card_commission_rate"
        @endif
        placeholder="Комиссия Card"
        value="{{ old('card_commission_rate') ?? $user->card_commission_rate ?? 0 }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="yookassa-commission-rate-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Комиссия YooKassa
    </label>
    <input
        type="text"
        id="yookassa-commission-rate-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][yookassa_commission_rate]"
        @else
            name="yookassa_commission_rate"
        @endif
        placeholder="Комиссия YooKassa"
        value="{{ old('yookassa_commission_rate') ?? $user->yookassa_commission_rate ?? 0 }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="yandex-commission-rate-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Комиссия Yandex
    </label>
    <input
        type="text"
        id="yandex-commission-rate-{{ $user->id ?? '' }}"
        @isset($user->id)
            name="users[{{ $user->id }}][yandex_commission_rate]"
        @else
            name="yandex_commission_rate"
        @endif
        placeholder="Комиссия Yandex"
        value="{{ old('yandex_commission_rate') ?? $user->yandex_commission_rate ?? 0 }}"
        class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full sm:w-1/4">
<label for="agent-commission-rate-{{ $user->id ?? '' }}" class="text-gray-700 font-medium mb-2">
    Агенская комиссия
</label>
<input
    type="text"
    id="agent-commission-rate-{{ $user->id ?? '' }}"
    @isset($user->id)
        name="users[{{ $user->id }}][agent_commission_rate]"
    @else
        name="agent_commission_rate"
    @endif
    placeholder="Агенская комиссия"
    value="{{ old('agent_commission_rate') ?? $user->agent_commission_rate ?? 0 }}"
    class="py-1.5 px-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
/>
</div>
