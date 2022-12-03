<?php
include 'Telegram.php';
require_once 'Users.php';
require_once 'Weather.php';
$e_message = "! Xatolik \n";
try {


    $key = "7c0e4d45b1f30c3fb3bbe6bdd8b00a98";

//History
//http://api.weatherapi.com/v1/future.json?key=a85c63aee77341ee89b50718223004&q=Urganch&dt=2022-10-16

    $bot_token = "5915718443:AAGsEKZF4DJ-5GONc0yvIEq4fUtLR4okYnw";
    $telegram = new Telegram($bot_token);

    $chat_id = $telegram->ChatID();
    $text = $telegram->Text();

    $data = $telegram->getData();
    $message = $data['message'];

    $user = new User($chat_id);
    $weather = new Weather($key);
//$admin_chat_id = 967469906;

    $page = $user->getPage();

    if ($text == "/start") {
        chooseLanguage();
    } else {
        switch ($page) {
            case "language":
                switch ($text) {
                    case "English 🇺🇸":
                        $user->setLanguage("eng");
                        showMainPage();
                        break;
                    case "Русский 🇷🇺":
                        $user->setLanguage("ru");
                        showMainPage();
                        break;
                    case "O'zbek tili 🇺🇿":
                        $user->setLanguage("uz");
                        showMainPage();
                        break;
                }
                break;
            case "main":
                switch ($text) {
                    case $user->GetText("menu_now"):
                        $user->setPage("weather_now");
                        askLocation();
//                    $data = $weather->now("Urganch");
//                    foreach ($data as $item)
//                    SendMessage(json_encode($item, JSON_PRETTY_PRINT));
                        break;
                    case $user->GetText("menu_today"):
                        $user->setPage("weather_today");
                        askLocation();
//                    $data = $weather->today("Urganch");
//                    foreach ($data as $item)
//                    SendMessage(json_encode($item, JSON_PRETTY_PRINT));
                        break;
                    case $user->GetText("menu_tomorrow"):
                        $user->setPage("weather_tomorrow");
                        askLocation();
//                    $data = $weather->tomorrow("Urganch");
//                    foreach ($data as $item)
//                    SendMessage(json_encode($item, JSON_PRETTY_PRINT));
                        break;
                    case $user->GetText("menu_day"):
                        $user->setPage("weather_week");
                        askLocation();
//                    $data = $weather->week("Urganch");
//                    foreach ($data as $item)
//                    SendMessage(json_encode($item, JSON_PRETTY_PRINT));
                        break;
                    case $user->GetText("menu_settings"):
                        chooseLanguage();
                        break;
                }
                break;
            case "weather_now":
                switch ($text) {
                    case $user->GetText("write_location"):
                        $user->setPage("now");
                        askCity();
                        break;
                    default:
                        $latitude = $message['location']['latitude'];
                        $longitude = $message['location']['longitude'];
                        if ($latitude != NULL) {
                            $q = "$latitude,$longitude";
                            showWeatherNow($q);
                        }
                        break;
                }
                break;
            case "now":
                showWeatherNow($text);
                break;
            case "weather_today":
                switch ($text) {
                    case $user->GetText("write_location"):
                        $user->setPage("today");
                        askCity();
                        break;
                    default:
                        $latitude = $message['location']['latitude'];
                        $longitude = $message['location']['longitude'];
                        if ($latitude != NULL) {
                            $q = "$latitude,$longitude";
                            showWeatherToday($q);
                        }
                        break;
                }
                break;
            case "today":
                showWeatherToday($text);
                break;
            case "weather_tomorrow":
                switch ($text) {
                    case $user->GetText("write_location"):
                        $user->setPage("tomorrow");
                        askCity();
                        break;
                    default:
                        $latitude = $message['location']['latitude'];
                        $longitude = $message['location']['longitude'];
                        if ($latitude != NULL) {
                            $q = "$latitude,$longitude";
                            showWeatherTomorrow($q);
                        }
                        break;
                }
                break;
            case "tomorrow":
                showWeatherTomorrow($text);
                break;
            case "weather_week":
                switch ($text) {
                    case $user->GetText("write_location"):
                        $user->setPage("week");
                        askCity();
                        break;
                    default:
                        $latitude = $message['location']['latitude'];
                        $longitude = $message['location']['longitude'];
                        if ($latitude != NULL) {
                            $q = "$latitude,$longitude";
                            showWeatherWeek($q);
                        }
                        break;
                }
                break;
            case "week":
                showWeatherWeek($text);
                break;
        }
    }

} catch (Exception $e) {
    $e_message .= $e->getMessage()."\n Qator-";
    $e_message .= $e->getLine()."\n File-";
    $e_message .= $e->getFile();
    sendMessage($e_message);
}

function chooseLanguage()
{
    global $chat_id, $telegram, $user;
    $user->createUser($chat_id);
    $user->setPage("language");

    $text = "Please select a language.\nПожалуйста выберите язык.\nIltimos, tilni tanlang.";

    $options = [
        [$telegram->buildKeyboardButton("English 🇺🇸"), $telegram->buildKeyboardButton("Русский 🇷🇺"), $telegram->buildKeyboardButton("O'zbek tili 🇺🇿")]
    ];
    $keyboard = $telegram->buildKeyBoard($options, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showMainPage()
{
    global $chat_id, $telegram, $user;
    $user->setPage("main");

    $text = $user->GetText("main");

    $options = [
        [$telegram->buildKeyboardButton($user->GetText("menu_now"))],
        [$telegram->buildKeyboardButton($user->GetText("menu_today")), $telegram->buildKeyboardButton($user->GetText("menu_tomorrow"))],
        [$telegram->buildKeyboardButton($user->GetText("menu_day"))],
        [$telegram->buildKeyboardButton($user->GetText("menu_settings"))],
    ];
    $keyboard = $telegram->buildKeyBoard($options, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function askLocation()
{
    global $chat_id, $telegram, $user;
//    $user->setPage("main");

    $text = $user->GetText("text_location");

    $options = [
        [$telegram->buildKeyboardButton($user->GetText("write_location"))],
        [$telegram->buildKeyboardButton($user->GetText("send_location"), false, true)],
    ];
    $keyboard = $telegram->buildKeyBoard($options, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function askCity()
{
    global $chat_id, $telegram, $user;
    $text = $user->GetText("text_write_location");
    $content = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showWeatherNow($q)
{
    global $weather, $telegram, $chat_id, $user;
    $data = $weather->now($q);
    $text = $user->GetText("text_name") . $data['location']['name'] . "\n" .
        $user->GetText("text_temperature") . $data['current']['temp_c'] . "\n" .
        $user->GetText("text_wind") . $data['current']['wind_mph'] . "\n" .
        $user->GetText("text_humidity") . $data['current']['humidity'] . "\n" .
        $user->GetText("text_time") . $data['current']['last_updated'] . "\n";
    $content = [
        'chat_id' => $chat_id,
//        'photo' => $data['current']['condition']['icon'],
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showWeatherToday($q)
{
    global $weather, $telegram, $chat_id, $user;
    $data = $weather->today($q);
    $text = $user->GetText("text_name") . $data['location']['name'] . "\n";
    foreach ($data["forecast"]["forecastday"][0]["hour"] as $hour) {
        $text .= "----------------------------------------------------------------\n";
        $text .= $user->GetText("text_temperature") . $hour['temp_c'] . "\n";
        $text .= $user->GetText("text_wind") . $hour['wind_mph'] . "\n";
        $text .= $user->GetText("text_humidity") . $hour['humidity'] . "\n";
        $text .= $user->GetText("text_time") . $hour['time'] . "\n";
    }
    $content = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showWeatherTomorrow($q)
{
    global $weather, $telegram, $chat_id, $user;
    $data = $weather->tomorrow($q);
    $text = $user->GetText("text_name") . $data['location']['name'] . "\n";
    foreach ($data["forecast"]["forecastday"][0]["hour"] as $hour) {
        $text .= "----------------------------------------------------------------\n";
        $text .= $user->GetText("text_temperature") . $hour['temp_c'] . "\n";
        $text .= $user->GetText("text_wind") . $hour['wind_mph'] . "\n";
        $text .= $user->GetText("text_humidity") . $hour['humidity'] . "\n";
        $text .= $user->GetText("text_time") . $hour['time'] . "\n";
    }
    $content = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}

function showWeatherWeek($q)
{
    global $weather, $telegram, $chat_id, $user;
    $data = $weather->week($q);
    foreach ($data["forecast"]["forecastday"] as $item) {
        $text = $user->GetText("text_name") . $data['location']['name'] . "\n";
        foreach ($item["hour"] as $hour) {
            $text .= "----------------------------------------------------------------\n";
            $text .= $user->GetText("text_temperature") . $hour['temp_c'] . "\n";
            $text .= $user->GetText("text_wind") . $hour['wind_mph'] . "\n";
            $text .= $user->GetText("text_humidity") . $hour['humidity'] . "\n";
            $text .= $user->GetText("text_time") . $hour['time'] . "\n";
        }
        $content = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        $telegram->sendMessage($content);
    }
}

function SendMessage($text)
{
    global $chat_id, $telegram;
    $content = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];
    $telegram->sendMessage($content);
}


?>