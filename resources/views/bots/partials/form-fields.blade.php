<div class="flex flex-col w-full">
    <label class="text-gray-700 font-medium mb-2">
        Название бота
    </label>
    <input type="text" name="name"
        placeholder="Название бота"
        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('name') ?? $bot->name ?? '' }}"
    />

    <label class="text-gray-700 font-medium mb-2">
        Токен
    </label>
    <input type="text" name="token"
           placeholder="Токен"
           class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
            focus:ring-blue-500 focus:border-blue-500"
           value="{{ old('token') ?? $bot->token ?? '' }}"
    />

    <label class="text-gray-700 font-medium mb-2 mt-1">
        Текст команды старт
    </label>
    <textarea class="p-2" name="start_text" cols="30" rows="4">{{ old('start_text') ?? $bot->start_text ?? '' }}</textarea>
</div>
