<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Админка') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="app" class="min-h-screen bg-slate-950 text-slate-100 antialiased">
<div class="flex min-h-screen">
    <aside class="hidden md:flex md:w-64 flex-col border-r border-slate-800 bg-slate-950/90">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-500 shadow-lg shadow-sky-500/40" />
            <div class="flex flex-col">
                <span class="text-sm font-semibold tracking-tight text-slate-50">
                    {{ config('app.name', 'Админка') }}
                </span>
                <span class="text-xs text-slate-400">
                    {{ auth()->user()->email ?? '' }}
                </span>
            </div>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1 text-sm">
            <a href="{{ route('/') }}"
               class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-800/80 hover:text-slate-50 transition-colors">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                <span>Дэшборд</span>
            </a>
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-800/80 hover:text-slate-50 transition-colors">
                <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                <span>Пользователи</span>
            </a>
            <a href="{{ route('url-payments.list') }}"
               class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-800/80 hover:text-slate-50 transition-colors">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                <span>Ссылки на оплату</span>
            </a>
            <a href="{{ route('tb.incoming.payments') }}"
               class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-800/80 hover:text-slate-50 transition-colors">
                <span class="h-1.5 w-1.5 rounded-full bg-fuchsia-400"></span>
                <span>Платежи ТБ</span>
            </a>
            <a href="{{ route('reports-commission.index') }}"
               class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-800/80 hover:text-slate-50 transition-colors">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span>Агентские отчёты</span>
            </a>
        </nav>
        <div class="px-4 py-4 border-t border-slate-800">
            <form action="{{ route('auth.destroy') }}" method="POST" class="w-full">
                @method('DELETE')
                @csrf
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-800 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-700 transition-colors">
                    <span>Выйти</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="flex items-center justify-between border-b border-slate-800 bg-slate-950/80 px-4 py-3 md:hidden">
            <span class="text-sm font-semibold text-slate-50">
                {{ config('app.name', 'Админка') }}
            </span>
            <form action="{{ route('auth.destroy') }}" method="POST">
                @method('DELETE')
                @csrf
                <button class="text-xs font-semibold text-slate-300 hover:text-slate-50">
                    Выйти
                </button>
            </form>
        </header>

        @csrf
        <main class="flex-1">
            <div class="mx-auto w-full max-w-6xl px-4 py-6 md:px-8 md:py-8">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-[0_18px_45px_rgba(15,23,42,0.7)] backdrop-blur">
                    <div class="px-4 py-4 md:px-6 md:py-6">
                        {{ $slot }}
                    </div>
                </div>

                @if (session('success'))
                    <div role="alert"
                         class="mt-4 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        <p class="font-semibold">Успех</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div role="alert"
                         class="mt-4 rounded-xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                        <p class="font-semibold">Ошибка</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>


</body>

</html>
