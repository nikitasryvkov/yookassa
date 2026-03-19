@php $pid = $paymentPoint->id ?? ''; @endphp

<div class="flex flex-col w-full sm:w-1/2">
    <label for="name-{{ $pid }}" class="form-label">Название точки</label>
    <input type="text" id="name-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][name]" @else name="name" @endif
        placeholder="Название точки" class="form-input"
        value="{{ old('name') ?? $paymentPoint->name ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/2">
    <label for="payment_purpose-{{ $pid }}" class="form-label">Назначение платежа</label>
    <input type="text" id="payment_purpose-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][payment_purpose]" @else name="payment_purpose" @endif
        placeholder="Назначение платежа" class="form-input"
        value="{{ old('payment_purpose') ?? $paymentPoint->payment_purpose ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/2">
    <label for="merchant_id-{{ $pid }}" class="form-label">Merchant id</label>
    <input type="text" id="merchant_id-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][merchant_id]" @else name="merchant_id" @endif
        placeholder="merchant_id" class="form-input"
        value="{{ old('merchant_id') ?? $paymentPoint->merchant_id ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/2">
    <label for="account_id-{{ $pid }}" class="form-label">Account id</label>
    <input type="text" id="account_id-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][account_id]" @else name="account_id" @endif
        placeholder="account_id" class="form-input"
        value="{{ old('account_id') ?? $paymentPoint->account_id ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/2">
    <label for="customer_code-{{ $pid }}" class="form-label">Customer code</label>
    <input type="text" id="customer_code-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][customer_code]" @else name="customer_code" @endif
        placeholder="customer code" class="form-input"
        value="{{ old('customer_code') ?? $paymentPoint->customer_code ?? '' }}" />
</div>

<div class="flex flex-col w-full sm:w-1/2">
    <label for="yandex_token-{{ $pid }}" class="form-label">Yandex token</label>
    <input type="text" id="yandex_token-{{ $pid }}"
        @isset($paymentPoint->id) name="payment-points[{{ $pid }}][yandex_token]" @else name="yandex_token" @endif
        placeholder="Yandex token" class="form-input"
        value="{{ old('yandex_token') ?? $paymentPoint->yandex_token ?? '' }}" />
</div>
