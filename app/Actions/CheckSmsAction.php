<?php

namespace App\Actions;

use App\Models\User;
use Exception;

class CheckSmsAction
{
    public $user;
    public $message;

    private $confirmCode;
    private $maxSmsAttempts = 3;

    public function __construct($userPhone, $confirmCode)
    {
        $this->user = User::where('phone', $userPhone)->first();
        $this->confirmCode = $confirmCode;

    }

    public function execute(): bool
    {
        try {
            $this
                ->checkUser()
                ->checkAttempts()
                ->checkCode()
                ->action();
            return true;
        } catch (Exception $e) {
            $this->message = $e->getMessage();
            return false;
        }


    }

    private function checkUser()
    {
        if (!$this->user) {
            throw new Exception('Пользователь не найден.');
        }

        return $this;
    }

    private function checkAttempts()
    {
        if ($this->user->attempts > $this->maxSmsAttempts) {
            throw new Exception('Превышено число попыток подтверждения кода. Попробуйте заново отправить данные логина/регистрации.');
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkCode(): CheckSmsAction
    {
        if ($this->user->confirm_code != $this->confirmCode) {
            $this->user->increment('attempts');
            throw new Exception('Неверный код подтверждения.');
        }

        return $this;
    }

    private function action()
    {
        $this->user->update(['attempts' => 0, 'confirm_code' => 1]);
    }


}
