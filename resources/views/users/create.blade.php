<x-layout>
    <div class="mb-6">
        <h1 class="section-title">Создание пользователя</h1>
    </div>

    <div class="glass-card">
        <form action="{{ route('users.store') }}" autocomplete="off" method="POST">
            @csrf
            <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-end gap-4">
                @include('users.partials.user-form')
                @if ($errors->any())
                    <x-validation-errors />
                @endif
                <div class="w-full mt-2">
                    <button type="submit" class="btn-success">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Создать
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
