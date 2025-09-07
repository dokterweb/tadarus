<?php

namespace App\Services;

use Telegram\Bot\Api;

class TelegramBotService
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));  // Menggunakan token dari .env
    }

    public function sendMessage($chatId, $message)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $message
        ]);
    }

    public function getUpdates()
    {
        return $this->telegram->getUpdates();
    }

    public function setWebhook($url)
    {
        return $this->telegram->setWebhook(['url' => $url]);
    }

}
