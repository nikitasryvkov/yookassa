<x-layout>
    <div class="mb-6">
        <h1 class="section-title">Создание бота</h1>
    </div>

    <div class="glass-card max-w-2xl">
        <form action="{{ route('bots.store') }}" autocomplete="off" method="POST">
            @csrf
            @include('bots.partials.form-fields')
            @if ($errors->any())
                <x-validation-errors />
            @endif
            <div class="mt-4">
                <button type="submit" class="btn-success">Создать</button>
            </div>
        </form>
    </div>
</x-layout>
