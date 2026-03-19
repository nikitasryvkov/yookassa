@if ($errors->any())
    <div class="alert-error mb-4">
        <p class="font-semibold mb-1">Обнаружены ошибки:</p>
        <ul class="list-disc pl-5 space-y-0.5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
