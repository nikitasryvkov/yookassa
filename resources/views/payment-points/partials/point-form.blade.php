<div class="flex flex-col w-full">
    <label for="name-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Название точки
    </label>
    <input
        type="text"
        id="name-{{ $paymentPoint->id ?? '' }}"
        @isset($paymentPoint->id)
            name="payment-points[{{ $paymentPoint->id }}][name]"
        @else
            name="name"
        @endif
        placeholder="Название точки"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('name') ?? $paymentPoint->name ?? '' }}"
    />

</div>

<div class="flex flex-col w-full">
    <label for="payment_purpose-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Назначение платежа
    </label>
    <input
        type="text"
        id="payment_purpose-{{ $paymentPoint->id ?? '' }}"
        @isset($paymentPoint->id)
            name="payment-points[{{ $paymentPoint->id }}][payment_purpose]"
        @else
            name="payment_purpose"
        @endif
        placeholder="назначение платежа"
        value="{{ old('payment_purpose') ?? $paymentPoint->payment_purpose ?? '' }}"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full">
    <label for="merchant_id-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Merchant id
    </label>
    <input
        type="text"
        id="merchant_id-{{ $paymentPoint->id ?? '' }}"
        @isset($paymentPoint->id)
            name="payment-points[{{ $paymentPoint->id }}][merchant_id]"
        @else
            name="merchant_id"
        @endif
        placeholder="merchant_id"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('merchant_id') ?? $paymentPoint->merchant_id ?? '' }}"
    />
</div>

<div class="flex flex-col w-full">
    <label for="account_id-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Account id
    </label>
        <input
            type="text"
            id="account_id-{{ $paymentPoint->id ?? '' }}"
            @isset($paymentPoint->id)
                name="payment-points[{{ $paymentPoint->id }}][account_id]"
            @else
                name="account_id"
            @endif
            placeholder="account_id"
            class="pl-4 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('account_id') ?? $paymentPoint->account_id ?? '' }}"
        />
</div>

<div class="flex flex-col w-full">
    <label for="customer_code-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Customer code
    </label>
    <input
        type="text"
        id="customer_code-{{ $paymentPoint->id ?? '' }}"
        @isset($paymentPoint->id)
            name="payment-points[{{ $paymentPoint->id }}][customer_code]"
        @else
            name="customer_code"
        @endif
        placeholder="customer code"
        value="{{ old('customer_code') ?? $paymentPoint->customer_code ?? '' }}"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
    />
</div>

<div class="flex flex-col w-full">
    <label for="yandex_token-{{ $paymentPoint->id ?? '' }}" class="text-gray-700 font-medium mb-2">
        Yandex token
    </label>
    <input
        type="text"
        id="yandex_token-{{ $paymentPoint->id ?? '' }}"
        @isset($paymentPoint->id)
            name="payment-points[{{ $paymentPoint->id }}][yandex_token]"
        @else
            name="yandex_token"
        @endif
        placeholder="Yandex token"
        value="{{ old('yandex_token') ?? $paymentPoint->yandex_token ?? '' }}"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
    />
</div>
