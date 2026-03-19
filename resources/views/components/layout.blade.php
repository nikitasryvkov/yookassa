<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Админка') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="app" class="min-h-screen bg-slate-950 text-slate-100 antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-transition.opacity
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm md:hidden"
         @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800/80 bg-slate-950/95 backdrop-blur-md transition-transform duration-300 md:static md:translate-x-0 md:w-64">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800/60">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/30">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-sm font-bold tracking-tight text-slate-50 truncate">
                    {{ config('app.name', 'Админка') }}
                </span>
                <span class="text-xs text-slate-500 truncate">
                    {{ auth()->user()->email ?? '' }}
                </span>
            </div>
        </div>

        {{-- Navigation --}}
        @php
            $currentRoute = request()->route()?->getName() ?? '';
        @endphp
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <a href="{{ url('/') }}"
               @class(['nav-link', 'active' => $currentRoute === 'welcome' || request()->is('/')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Дэшборд</span>
            </a>

            <a href="{{ route('users.index') }}"
               @class(['nav-link', 'active' => str_starts_with($currentRoute, 'users.')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span>Пользователи</span>
            </a>

            <a href="{{ route('url-payments.list') }}"
               @class(['nav-link', 'active' => str_starts_with($currentRoute, 'url-payments.')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
                <span>Ссылки на оплату</span>
            </a>

            <a href="{{ route('tb.incoming.payments') }}"
               @class(['nav-link', 'active' => str_starts_with($currentRoute, 'tb.')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                <span>Платежи ТБ</span>
            </a>

            <a href="{{ route('reports-commission.index') }}"
               @class(['nav-link', 'active' => str_starts_with($currentRoute, 'reports-commission.')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span>Агентские отчёты</span>
            </a>

            @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-2 px-3">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-600">Управление</span>
                </div>

                <a href="{{ route('payment-points.index') }}"
                   @class(['nav-link', 'active' => str_starts_with($currentRoute, 'payment-points.')])>
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                    <span>Платёжные точки</span>
                </a>

                <a href="{{ route('bots.index') }}"
                   @class(['nav-link', 'active' => str_starts_with($currentRoute, 'bots.')])>
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                    </svg>
                    <span>Боты</span>
                </a>

                <a href="{{ route('telegram-payments.list') }}"
                   @class(['nav-link', 'active' => str_starts_with($currentRoute, 'telegram-payments.')])>
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    <span>Платежи Telegram</span>
                </a>
            @endif
        </nav>

        {{-- Sidebar footer --}}
        <div class="border-t border-slate-800/60 px-3 py-3">
            <a href="{{ route('profile.index') }}"
               @class(['nav-link mb-1', 'active' => str_starts_with($currentRoute, 'profile.')])>
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Профиль</span>
            </a>
            <form action="{{ route('auth.destroy') }}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit" class="nav-link w-full text-red-400/80 hover:bg-red-500/10 hover:text-red-400">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    <span>Выйти</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex flex-1 flex-col min-w-0">
        {{-- Mobile header --}}
        <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-800/60 bg-slate-950/90 backdrop-blur-md px-4 py-3 md:hidden">
            <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-slate-100 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <span class="text-sm font-bold text-slate-50">{{ config('app.name', 'Админка') }}</span>
            <div class="w-8"></div>
        </header>

        @csrf
        <main class="flex-1 animate-fade-in">
            <div class="mx-auto w-full max-w-7xl px-4 py-6 md:px-8 md:py-8">
                {{ $slot }}

                @if (session('success'))
                    <div role="alert" class="alert-success mt-6">
                        <p class="font-semibold">Успех</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div role="alert" class="alert-error mt-6">
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
