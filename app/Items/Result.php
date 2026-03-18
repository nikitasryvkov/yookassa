<?php

namespace App\Items;

/**
 * Общий класс результата выполняения кода
 * Class Result
 * @package App\Items
 */
class Result
{
    public int $success = 0;

    public string $error = '';

    public int $id = 0;

    /**
     * @var array<string, mixed>
     */
    public array $data;

    public function setSuccess(int $id = 0): Result
    {
        if ($id != 0) {
            $this->id = $id;
        }
        $this->success = 1;

        return $this;
    }

    public function setError(string $error): Result
    {
        $this->success = 0;
        $this->error = $error;

        return $this;
    }
}
