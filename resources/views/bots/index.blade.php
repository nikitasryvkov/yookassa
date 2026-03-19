<x-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="section-title">Настройка ботов</h1>
        <a href="{{ route('bots.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Добавить
        </a>
    </div>

    @if ($errors->any())
        <x-validation-errors />
    @endif

    <div class="space-y-4">
        @foreach($bots as $bot)
            <div class="glass-card animate-slide-up">
                <form action="{{ route('bots.update', $bot) }}" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')
                    @include('bots.partials.form-fields')

                    <div class="mt-3">
                        <label for="secret-{{ $bot->id }}" class="form-label">Секретный токен</label>
                        <input type="text" name="x_telegram_bot_api_secret_token" id="secret-{{ $bot->id }}"
                            placeholder="Секретный токен" class="form-input"
                            value="{{ old('x-telegram-bot-api-secret-token') ?? $bot->x_telegram_bot_api_secret_token ?? '' }}" />
                    </div>
                    <input type="hidden" name="id" value="{{ $bot->id }}">

                    <div class="info-{{ $bot->id }}"></div>

                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <button type="submit" class="btn-success">Сохранить</button>
                        <button type="button" class="btn-danger"
                            onclick="if(confirm('Вы уверены, что хотите удалить этого бота?')) { document.getElementById('delete-form-{{ $bot->id }}').submit(); }">
                            Удалить
                        </button>
                        <set-telegram-webhook-button :id="{{ json_encode($bot->id) }}" :token="{{ json_encode($bot->token) }}" :secret="{{ json_encode($bot->secret) }}"></set-telegram-webhook-button>
                    </div>
                </form>

                <form id="delete-form-{{ $bot->id }}" action="{{ route('bots.destroy', $bot) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endforeach
    </div>
</x-layout>
