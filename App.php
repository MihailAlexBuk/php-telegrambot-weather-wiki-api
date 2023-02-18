<?php

require_once "Bot.php";
require_once "Api/Weather.php";
require_once "Api/Wiki.php";

class App
{
    private Weather $weatherApi;
    private Wiki $wikiApi;
    private Bot $bot;

    private $temp = '';

    public function __construct()
    {
        $this->bot = new Bot();
        $this->weatherApi = new Weather();
        $this->wikiApi = new Wiki();
    }

    public function start()
    {
        while (true)
        {
            $updates = $this->bot->getUpdates();

            foreach ($updates as $update)
            {
                $message = $update->message;
                $text = $message->text;
                $chat_id = $message->chat->id;

                if($text == '/start'){
                    $this->bot->menuButtons($chat_id, 'How can i help you?');
                }
//    Weather
                elseif($text == "\xE2\x98\x80 Weather"){
                    $this->bot->weatherButtons($chat_id, 'Select weather forecast');
                }elseif($text == "\xE2\x9D\x8C Back"){
                    $this->temp = '';
                    $this->bot->menuButtons($chat_id, 'How can i help you?');
                }elseif($text == "\xE2\x98\x80 Today"){
                    $this->temp = $text;
                    $this->bot->sendMessage($chat_id, 'Enter city');
                }elseif($text == "\xE2\x98\x81 For 5 days"){
                    $this->temp = $text;
                    $this->bot->sendMessage($chat_id, 'Enter city');
                }elseif($this->temp == "\xE2\x98\x80 Today"){
                    $weather = $this->weatherApi->getWeatherForToday($text);
                    $this->bot->sendMessage($chat_id, $weather['content']);
                    if($weather['code']){
                        $this->temp = '';
                        $this->bot->menuButtons($chat_id, 'How can i help you?');
                    }else{
                        $this->temp = "\xE2\x98\x80 Today";
                        $this->bot->sendMessage($chat_id, 'Enter city');
                    }
                }elseif($this->temp == "\xE2\x98\x81 For 5 days"){
                    $weather = $this->weatherApi->getWeatherFor5Days($text);
                    $this->bot->sendMessage($chat_id, $weather['content']);
                    if($weather['code']){
                        $this->temp = '';
                        $this->bot->menuButtons($chat_id, 'How can i help you?');
                    }else{
                        $this->temp = "\xE2\x98\x81 For 5 days";
                        $this->bot->sendMessage($chat_id, 'Enter city');
                    }
                }
//    WIKI
                elseif($text == "\xF0\x9F\x8C\x8D Wikipedia"){
                    $this->temp = $text;
                    $this->bot->sendMessage($chat_id, 'What to find?');
                }elseif ($this->temp == "\xF0\x9F\x8C\x8D Wikipedia"){
                    $wiki = $this->wikiApi->get_wiki($text);
                    $this->bot->sendMessage($chat_id, $wiki['content']);
                    if($wiki['code']){
                        $this->temp = '';
                        $this->bot->menuButtons($chat_id, 'How can i help you?');
                    }else{
                        $this->temp = "\xF0\x9F\x8C\x8D Wikipedia";
                        $this->bot->sendMessage($chat_id, 'Repeat request');
                    }
                }

                else{
                    $this->temp = '';
                    $this->bot->sendMessage($chat_id, 'I don\'t understand');
                }

            }
        }

    }

}