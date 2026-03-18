<x-layout>
    @foreach($paymentPoints as $paymentPoint)
        <form action="{{ route('payment-points.update', [$paymentPoint]) }}" class="mb-2 bg-sky-100" autocomplete="off" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-center gap-4 p-6 rounded-lg shadow-md">
                @include('payment-points.partials.point-form')
                @if ($errors->hasAny(['payment-points.' . $paymentPoint->id . '.name', 'payment-points.' . $paymentPoint->id . '.payment_purpose',
                    'payment-points.' . $paymentPoint->id . '.merchant_id', 'payment-points.' . $paymentPoint->id . '.account_id', 'payment-points.' . $paymentPoint->id . '.customer_code',
                    'payment-points.' . $paymentPoint->id . '.yandex_token']))
                    <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded w-full">
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
                    <button
                        type="submit"
                        class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-600
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        &check;
                    </button>
                    <delete-point-btn id="{{ json_encode($paymentPoint->id) }}"></delete-point-btn>
                </div>
            </div>
        </form>
    @endforeach
</x-layout>
