@php $uid = $user->id ?? ''; @endphp

<div class="flex flex-col w-full sm:w-1/3">
    <label for="name-{{ $uid }}" class="form-label">Имя</label>
    <input type="text" id="name-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][name]" @else name="name" @endif
        placeholder="Имя" class="form-input"
        value="{{ old('name') ?? $user->name ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="email-{{ $uid }}" class="form-label">Email</label>
    <input type="text" id="email-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][email]" @else name="email" @endif
        placeholder="Почта" class="form-input"
        value="{{ old('email') ?? $user->email ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="password-{{ $uid }}" class="form-label">Новый пароль</label>
    <div class="relative">
        <input data-psw="{{ Str::password(8) }}" type="text" id="password-{{ $uid }}"
            @isset($user->id) name="users[{{ $uid }}][password]" @else name="password" @endif
            placeholder="Новый пароль" class="form-input pr-14"
            @empty($user) value="{{ old('password') ?? Str::password(8) }}" @endempty />
        @isset($user)
            <button title="Предложить пароль" type="button"
                class="absolute right-0 top-0 h-full rounded-r-xl bg-blue-600 px-3 text-xs font-semibold text-white hover:bg-blue-500 transition-colors"
                onclick="const input = document.getElementById('password-{{$user->id}}'); input.value = input.dataset.psw">
                psw
            </button>
        @endisset
    </div>
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="telegram-id-{{ $uid }}" class="form-label">Telegram id</label>
    <input type="text" id="telegram-id-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][telegram_id]" @else name="telegram_id" @endif
        placeholder="Telegram id" class="form-input"
        value="{{ old('telegram_id') ?? $user->telegram_id ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="counterparty-name-{{ $uid }}" class="form-label">Получатель платежа</label>
    <input type="text" id="counterparty-name-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][counterparty_name]" @else name="counterparty_name" @endif
        placeholder="Получатель платежа" class="form-input"
        value="{{ old('counterparty_name') ?? $user->counterparty_name ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="account-number-{{ $uid }}" class="form-label">Счёт (20 симв.)</label>
    <input type="text" id="account-number-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][account_number]" @else name="account_number" @endif
        placeholder="Счёт получателя" class="form-input"
        value="{{ old('account_number') ?? $user->account_number ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="payment-purpose-{{ $uid }}" class="form-label">Назначение платежа</label>
    <input type="text" id="payment-purpose-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][payment_purpose]" @else name="payment_purpose" @endif
        placeholder="Назначение платежа" class="form-input"
        value="{{ old('payment_purpose') ?? $user->payment_purpose ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="bic-{{ $uid }}" class="form-label">БИК получателя</label>
    <input type="text" id="bic-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][bic]" @else name="bic" @endif
        placeholder="Бик получателя" class="form-input"
        value="{{ old('bic') ?? $user->bic ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="role-{{ $uid }}" class="form-label">Роль</label>
    <select @isset($user->id) name="users[{{ $uid }}][role_id]" @else name="role_id" @endif
        id="role-{{ $uid }}" class="form-input">
        @foreach($roles as $id => $role)
            <option @if(isset($user) && $user->role_id === $id || old('role_id') == $id) selected @endif value="{{ $id }}">{{ $role['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="type-{{ $uid }}" class="form-label">Тип</label>
    <select @isset($user->id) name="users[{{ $uid }}][type_id]" @else name="type_id" @endif
        id="type-{{ $uid }}" class="form-input">
        @foreach($types as $id => $type)
            <option @if(isset($user) && $user->type_id === $id || old('type_id') == $id) selected @endif value="{{ $id }}">{{ $type['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col w-full sm:w-1/3">
    <label for="payment_point_id-{{ $uid }}" class="form-label">Точка продаж</label>
    <select @isset($user->id) name="users[{{ $uid }}][payment_point_id]" @else name="payment_point_id" @endif
        id="payment_point_id-{{ $uid }}" class="form-input">
        <option value="">Не выбрана</option>
        @foreach($paymentPoints as $id => $point)
            <option @if(isset($user) && $user->payment_point_id === $id || old('payment_point_id') == $id) selected @endif value="{{ $id }}">{{ $point['name'] }}</option>
        @endforeach
    </select>
</div>

{{-- Commission rates --}}
<div class="flex flex-col w-full sm:w-1/4">
    <label for="qr-commission-rate-{{ $uid }}" class="form-label">Комиссия QR</label>
    <input type="text" id="qr-commission-rate-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][qr_commission_rate]" @else name="qr_commission_rate" @endif
        placeholder="0" class="form-input"
        value="{{ old('qr_commission_rate') ?? $user->qr_commission_rate ?? 0 }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="card-commission-rate-{{ $uid }}" class="form-label">Комиссия Card</label>
    <input type="text" id="card-commission-rate-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][card_commission_rate]" @else name="card_commission_rate" @endif
        placeholder="0" class="form-input"
        value="{{ old('card_commission_rate') ?? $user->card_commission_rate ?? 0 }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="yookassa-commission-rate-{{ $uid }}" class="form-label">Комиссия YooKassa</label>
    <input type="text" id="yookassa-commission-rate-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][yookassa_commission_rate]" @else name="yookassa_commission_rate" @endif
        placeholder="0" class="form-input"
        value="{{ old('yookassa_commission_rate') ?? $user->yookassa_commission_rate ?? 0 }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="yandex-commission-rate-{{ $uid }}" class="form-label">Комиссия Yandex</label>
    <input type="text" id="yandex-commission-rate-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][yandex_commission_rate]" @else name="yandex_commission_rate" @endif
        placeholder="0" class="form-input"
        value="{{ old('yandex_commission_rate') ?? $user->yandex_commission_rate ?? 0 }}" />
</div>

<div class="flex flex-col w-full sm:w-1/4">
    <label for="agent-commission-rate-{{ $uid }}" class="form-label">Агентская комиссия</label>
    <input type="text" id="agent-commission-rate-{{ $uid }}"
        @isset($user->id) name="users[{{ $uid }}][agent_commission_rate]" @else name="agent_commission_rate" @endif
        placeholder="0" class="form-input"
        value="{{ old('agent_commission_rate') ?? $user->agent_commission_rate ?? 0 }}" />
</div>
