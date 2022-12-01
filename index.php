<?php
//weather bot
$e_message = "! Xatolik \n";
require_once  "Telegram.php";
try {
$telegram = new Telegram("5915718443:AAGsEKZF4DJ-5GONc0yvIEq4fUtLR4okYnw");
$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$start = "Assalomu alaykum men ob-havo botiman. Men sizga dunyoning istalgan shahrining ob-havosini ayta olaman. Faqat shahar nomini yozing, men sizga ob-havoni aytib beraman. Masalan, 'London' deb yozing va men sizga Londonning ob-havosini aytib beraman.
 Buyruqlar ro'yxatini ko'rish uchun 'Yordam' ni bosing.";
switch ($text) {
    case "/start":
        start();
        break;
    case "help":
        $reply = "Bu bot sizga dunyoning istalgan shahrining ob-havosini aytib berishi mumkin";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
        break;
    default:
        $reply = "Bunday buyruq mavjud emas 🥺";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
        break;
}
}  catch (Throwable $e) {
    $e_message .= $e->getMessage()."\n Qator-";
    $e_message .= $e->getLine()."\n File-";
    $e_message .= $e->getFile();
    sendMessage($e_message);

}

function start()
{
    global $telegram;
    global $chat_id;
    $option = [
        ["Shahar nomini yuboring"],
        ["Yordam"],
    ];
    $keyb = $telegram->buildKeyBoard($option);
    $reply = "Botimizga Xush kelibsiz🌤️";
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $reply,
        'reply_markup' => $keyb
    ]);
}

function sendMessage($text)
{
    global $chat_id, $telegram;
    $content = [
        'chat_id' => $chat_id,
        'text' => $text
    ];
    $telegram->sendMessage($content);
}