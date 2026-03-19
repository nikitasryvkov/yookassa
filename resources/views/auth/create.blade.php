<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — {{ config('app.name', 'Админка') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased flex items-center justify-center px-4">
    <div class="w-full max-w-md animate-fade-in">
        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/30 mb-4">
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-50">{{ config('app.name', 'Админка') }}</h1>
            <p class="text-sm text-slate-500">Войдите в систему</p>
        </div>

        <div class="glass-card">
            <form action="{{ route('auth.store') }}" method="POST" autocomplete="off" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="form-label">E-mail <span class="text-red-400">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="form-input" placeholder="user@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Пароль <span class="text-red-400">*</span></label>
                    <input id="password" name="password" type="password" required
                        class="form-input" placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer">
                        <input id="remember" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/30">
                        Запомнить
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Войти
                </button>
            </form>
        </div>
    </div>
</body>
</html>
