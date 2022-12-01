<?php
//weather bot

$telegram = new Telegram("5915718443:AAGsEKZF4DJ-5GONc0yvIEq4fUtLR4okYnw");
$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$start = "Assalomu alaykum men ob-havo botiman. Men sizga dunyoning istalgan shahrining ob-havosini ayta olaman. Faqat shahar nomini yozing, men sizga ob-havoni aytib beraman. Masalan, 'London' deb yozing va men sizga Londonning ob-havosini aytib beraman.
 Buyruqlar ro'yxatini ko'rish uchun 'Yordam' ni bosing.";
$help = "/help";

switch ($text) {
    case $start:
        $reply = "Ob havo botiga Xush  kelibsiz 🌤️";
        start();
        break;
    case $help:
        $reply = "Bu bot sizga dunyoning istalgan shahrining ob-havosini aytib berishi mumkin";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
        break;
    default:
        $reply = "Bunday buyruq mavjud emas 🥺";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
        break;
}
function start()
{
    global $telegram;
    global $chat_id;
    $option = [
        ["Shahar nomini tanlang"]
        ["Yordam"],

    ];
    $keyb = $telegram->buildKeyBoard($option);
    $reply = "Welcome to Weather Bot";
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $reply,
        'reply_markup' => $keyb
    ]);
}