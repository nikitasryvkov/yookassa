<div class="space-y-3 w-full">
    <div>
        <label for="name-{{ $bot->id ?? '' }}" class="form-label">Название бота</label>
        <input type="text" name="name" id="name-{{ $bot->id ?? '' }}" placeholder="Название бота"
            class="form-input" value="{{ old('name') ?? $bot->name ?? '' }}" />
    </div>

    <div>
        <label for="token-{{ $bot->id ?? '' }}" class="form-label">Токен</label>
        <input type="text" name="token" id="token-{{ $bot->id ?? '' }}" placeholder="Токен"
            class="form-input" value="{{ old('token') ?? $bot->token ?? '' }}" />
    </div>

    <div>
        <label for="start-text-{{ $bot->id ?? '' }}" class="form-label">Текст команды старт</label>
        <textarea name="start_text" id="start-text-{{ $bot->id ?? '' }}" rows="4"
            class="form-input">{{ old('start_text') ?? $bot->start_text ?? '' }}</textarea>
    </div>
</div>
