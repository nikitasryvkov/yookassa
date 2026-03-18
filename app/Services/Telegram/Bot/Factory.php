<?php

namespace App\Services\Telegram\Bot;

class Factory
{

    public function __construct(
        private readonly Message $message,
        private readonly File    $file,
        private readonly InlineQuery $inlineQuery,
        private readonly Buttons $buttons,
    )
    {}

    //срабатывает когда вызывается какой-либо метод
    public function __call(string $name, array $arguments)
    {
        foreach ($this as $key => $prop)
        {
            if(method_exists($this->$key, $name)) {
                return call_user_func_array([$this->$key, $name], $arguments);
            }
        }

        throw new \Exception("Метода $name не существует");
    }
}
