<x-layout>

    <div class="text-lg font-semibold text-center">Создание бота</div>
    <br>
    <div class="p-6 rounded-lg shadow-md">
        <form action="{{ route('bots.store') }}" class="mb-2 bg-sky-100" autocomplete="off" method="POST">
            @csrf
            @include('bots.partials.form-fields')
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded w-full mt-2">
                    <h4 class="font-bold">Ошибки:</h4>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="mt-2 px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-600
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Создать
                </button>
            </div>
        </form>
    </div>
</x-layout>
