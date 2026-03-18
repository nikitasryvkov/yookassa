<form
    method="POST"
    action="{{ empty($user->paymentConditions) ? route('user-payment-condition.store') : route('user-payment-condition.update') }}">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
        <div class="mb-2">
            <x-label for="rate-{{ $user->id }}">Тариф</x-label>
            <x-text-input name="rate-{{ $user->id }}" value="{{ $user->paymentConditions->rate ?? '' }}"></x-text-input>
        </div>

        <div class="mb-2">
            <x-label for="commission-{{ $user->id }}">Комиссия за вывод</x-label>
            <x-text-input name="commission-{{ $user->id }}" value="{{ $user->paymentConditions->commission ?? '' }}"></x-text-input>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
        <div>
            <x-label for="limit-{{ $user->id }}">Лимит вывода без комиссии</x-label>
            <x-text-input name="limit-{{ $user->id }}" value="{{ $user->paymentConditions->limit ?? '' }}"></x-text-input>
        </div>
    </div>
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <x-button variant="success" class="mt-2">Сохранить</x-button>
</form>
