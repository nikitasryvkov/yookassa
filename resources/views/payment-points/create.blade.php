<x-layout>
    <form action="{{ route('payment-points.store') }}" class="mb-2 bg-sky-100" autocomplete="off" method="POST">
        @csrf
        <div class="flex flex-col sm:flex-row sm:flex-wrap md:items-center gap-4 p-6 rounded-lg shadow-md">
            @include('payment-points.partials.point-form')
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded w-full">
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
                    class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-600
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Создать
                </button>
            </div>
        </div>
    </form>
</x-layout>
