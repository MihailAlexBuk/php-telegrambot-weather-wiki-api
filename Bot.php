<?php

use GuzzleHttp\Client;

class Bot
{
    protected $updateId;

    public function query($method = 'getMe', $params = [])
    {
        $url = "https://api.telegram.org/bot".$_ENV['TELEGRAM_BOT_TOKEN']."/".$method;;

        if(!empty($params)){
            $url .= "?" . http_build_query($params);
        }
        $client = new Client([
            'base_uri' => $url
        ]);

        $result = $client->request('GET');
        return json_decode($result->getBody());
    }

    public function getUpdates()
    {
        $response = $this->query('getUpdates', [
            'offset' => $this->updateId + 1
        ]);

        if(!empty($response->result)){
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }
        return $response->result;
    }

    public function sendMessage($chat_id, $text)
    {
        $text = strip_tags($text);
        $response = $this->query("sendMessage", [
            'text' => $text,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
        ]);
        return $response;
    }

    public function menuButtons($chat_id, $message)
    {
        $response = $this->query("sendMessage", [
            'text' => $message,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
            'disable_web_page_preview' => false,
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => "\xE2\x98\x80 Weather",'callback_data' => 'weather'],
                        ['text' => "\xF0\x9F\x8C\x8D Wikipedia",'callback_data' => 'wiki'],
                    ],
                ],
            ]),
        ]);
        return $response;
    }

    public function weatherButtons($chat_id, $message)
    {
        $response = $this->query("sendMessage", [
            'text' => $message,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
            'disable_web_page_preview' => false,
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => "\xE2\x98\x80 Today",'callback_data' => 'weatherToday'],
                        ['text' => "\xE2\x98\x81 For 5 days",'callback_data' => 'weatherFor5Days'],
                    ],
                    [
                        ['text' => "\xE2\x9D\x8C Back",'callback_data' => 'back'],
                    ]
                ],
            ]),
        ]);
        return $response;
    }
}