<x-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="section-title">Платёжные точки</h1>
        <a href="{{ route('payment-points.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Добавить
        </a>
    </div>

    <div class="space-y-4">
        @foreach($paymentPoints as $paymentPoint)
            <div class="glass-card animate-slide-up">
                <form action="{{ route('payment-points.update', [$paymentPoint]) }}" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-end gap-4">
                        @include('payment-points.partials.point-form')

                        @if ($errors->hasAny(['payment-points.' . $paymentPoint->id . '.name', 'payment-points.' . $paymentPoint->id . '.payment_purpose',
                            'payment-points.' . $paymentPoint->id . '.merchant_id', 'payment-points.' . $paymentPoint->id . '.account_id', 'payment-points.' . $paymentPoint->id . '.customer_code',
                            'payment-points.' . $paymentPoint->id . '.yandex_token']))
                            <div class="alert-error w-full">
                                <h4 class="font-bold">Ошибки:</h4>
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->get('payment-points.' . $paymentPoint->id . '.*') as $fieldErrors)
                                        @foreach ($fieldErrors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <button type="submit" class="btn-success">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </button>
                            <delete-point-btn id="{{ json_encode($paymentPoint->id) }}"></delete-point-btn>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</x-layout>
