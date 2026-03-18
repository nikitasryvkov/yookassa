<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotRequest;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['bots'] = Bot::query()->get();
        return view('bots.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bots.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BotRequest $request)
    {
        Bot::query()->create($request->validated());
        return redirect()->route('bots.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BotRequest $request, Bot $bot)
    {
        $bot->fill($request->validated())->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bot $bot)
    {
        $bot->delete();
        return redirect()->route('bots.index')->with('success', 'Бот успешно удален');
    }

    public function setTelegramWebhook(
        Request $request
    )
    {
        $token = $request->post('token');
        if(empty($token)) {
            return response()->json(['result' => 'Ошибка, отсутствует токен']);
        }

        $secret = $request->post('secret');
        if(empty($secret)) {
            $secret = Str::random(26);
            $bot = Bot::query()->where('id', $request->post('id'))->first();
            if(is_null($bot))
            {
                return response()->json(['result' => 'Ошибка, бот не найден']);
            }

            $bot->x_telegram_bot_api_secret_token = $secret;
            $bot->save();
        }

        $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => route('telegram.webhook') ,
            'secret_token' => $secret,  // секретный токен
        ]);

        // Проверяем успешность запроса
        if ($response->successful()) {
            return response()->json([
                'result' => 'Секретный токен: ' . $secret . '. Вэбхук успешно установлен.' . 'Url: ' .
                    route('telegram.webhook')
            ]);
        }

        // Если что-то пошло не так
        return response()->json(['result' => 'Секретный токен: ' . $secret . '. Ошибка установки вэбхука']);
    }
}
