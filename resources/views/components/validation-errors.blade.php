@if ($errors->any())
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 p-4">
        <h2 class="mb-2 text-sm font-semibold text-red-700">Обнаружены ошибки:</h2>
        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
