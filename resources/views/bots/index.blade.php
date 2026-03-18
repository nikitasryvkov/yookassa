<x-layout>

    <div class="text-lg font-semibold text-center">Натройка ботов</div>
    <br>

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

    @foreach($bots as $bot)
        <div class="p-6 rounded-lg shadow-md">
            <form action="{{ route('bots.update', $bot) }}" class="mb-2 bg-sky-100" autocomplete="off" method="POST">
                @csrf
                @method('PUT')
                @include('bots.partials.form-fields')
                <div class="flex flex-col w-full">
                    <label class="text-gray-700 font-medium mb-2">
                        Секретный токен
                    </label>
                    <input type="text" name="x_telegram_bot_api_secret_token"
                           placeholder="Секретный токен"
                           class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                       focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('x-telegram-bot-api-secret-token') ?? $bot->x_telegram_bot_api_secret_token ?? '' }}"
                    />
                </div>
                <input type="hidden" name="id" value="{{ $bot->id }}">

                <div class="info-{{ $bot->id }}"></div>
                <div class="flex items-center mt-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Сохранить
                    </button>

                    <button type="button"
                            onclick="if(confirm('Вы уверены, что хотите удалить этого бота?')) {
                        document.getElementById('delete-form').submit();
                    }"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 ml-2">
                        Удалить
                    </button>
                    <set-telegram-webhook-button :id="{{ json_encode($bot->id) }}" :token="{{ json_encode($bot->token) }}" :secret="{{ json_encode($bot->secret) }}"></set-telegram-webhook-button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('bots.destroy', $bot) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    @endforeach
</x-layout>
