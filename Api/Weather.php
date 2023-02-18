<?php

class Weather
{
    public function getWeatherForToday(string $city)
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?q='.$city.'&units=metric&appid='.$_ENV['OPEN_WEATHER_API'];
        $content = file_get_contents($url);

        if($content) {
            $weather = json_decode($content);
            $temp_now = round($weather->main->temp) . "°C";
            $today = date("l jS \of F Y, H:i");
            $cityname = $weather->name;
            $cloud = '';
            foreach ($weather->weather[0] as $k => $v) {
                if ($k === 'description') {
                    $cloud = $v;
                }
            }
            $res = "City : $cityname \nDate : $today \nTemperature : $temp_now \nClouds: $cloud";
            return ['content' => $res, 'code' => true];
        }else{
            return ['content' => "City {$city} not found!", 'code' => false];
        }
    }

    public function getWeatherFor5Days(string $city)
    {
        $url = 'http://api.openweathermap.org/data/2.5/forecast?q='.$city.'&units=metric&appid='.$_ENV['OPEN_WEATHER_API'];

        $content = file_get_contents($url);

        if($content) {
            $weather = json_decode($content);
            $cityName = $weather->city->name;
            $res = "City: $cityName";

            foreach ($weather->list as $list) {
                foreach ($list->weather[0] as $k => $v) {
                    if ($k === 'description') {
                        $cloud = $v;
                    }
                }
                $temp_now = round($list->main->temp) . "°C";
                $date = new DateTimeImmutable($list->dt_txt);
                $date = $date->format('l jS \of F Y, H:i');
                if (strpos($list->dt_txt, "12:00:00")) {
                    $res .= "\n<b>Date: $date</b> \n\tTemperature: $temp_now\n\tClouds: $cloud";
                } elseif (strpos($list->dt_txt, "00:00:00")) {
                    $res .= "\n<b>Date: $date</b> \n\tTemperature: $temp_now\n\tClouds: $cloud";
                }
            }
            return ['content' => $res, 'code' => true];
        }else{
            return ['content' => "City {$city} not found!", 'code' => false];
        }
    }

}
