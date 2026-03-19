<x-layout>
    <div class="mb-6">
        <h1 class="section-title">Создание платёжной точки</h1>
    </div>

    <div class="glass-card max-w-3xl">
        <form action="{{ route('payment-points.store') }}" autocomplete="off" method="POST">
            @csrf
            <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-end gap-4">
                @include('payment-points.partials.point-form')
                @if ($errors->any())
                    <x-validation-errors />
                @endif
                <div class="w-full mt-2">
                    <button type="submit" class="btn-success">Создать</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
