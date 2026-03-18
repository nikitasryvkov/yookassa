<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Админка</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="app" class="bg-gradient-to-r from-indigo-100 via-sky-100 to-emerald-100">

<header-vue :is-admin="{{ json_encode(auth()->user()->isAdmin()) }}">
    <form action="{{ route('auth.destroy') }}" method="POST">
        @method('DELETE')
        @csrf
        <button class="text-sm/6 font-semibold text-gray-900">Выйти</button>
    </form>
</header-vue>
@csrf
<div class="container from-10% via-30% to-90% mx-auto mt-10 max-w-4xl text-slate-700 min-h-screen" >
    {{ $slot }}
</div>

@if (session('success'))
    <div role="alert"
         class="my-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 opacity-75">
        <p class="font-bold">Success!</p>
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div role="alert"
         class="my-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 opacity-75">
        <p class="font-bold">Error!</p>
        <p>{{ session('error') }}</p>
    </div>
@endif


</body>

</html>
