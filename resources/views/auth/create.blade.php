<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Админка</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="from-10% via-30% to-90% mx-auto max-w-2xl bg-gradient-to-r from-indigo-100 via-sky-100 to-emerald-100 text-slate-700 content-center min-h-screen">
    <x-card class="py-8 px-16">
        <form action="{{ route('auth.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-8">
                <x-label for="email" :required="true">E-mail</x-label>
                <x-text-input name="email"/>
            </div>

            <div class="mb-8">
                <x-label for="password" :required="true">
                    Password
                </x-label>
                <x-text-input name="password" type="password"/>
            </div>

            <div class="mb-8 flex justify-between text-sm font-medium">
                <div>
                    <div class="flex items-center space-x-2">
                        <input id="remember" type="checkbox" name="remember"
                               class="rounded-sm border border-slate-400">
                        <label for="remember">Remember me</label>
                    </div>
                </div>
            </div>

            <x-button @class(['w-full'])>Войти</x-button>
        </form>
    </x-card>
</body>
</html>
