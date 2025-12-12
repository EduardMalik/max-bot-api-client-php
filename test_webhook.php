<?php

require __DIR__.'/vendor/autoload.php';

use BushlanovDev\MaxMessengerBot\Models\Updates\BotStartedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreated;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCallbackUpdate;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;

$env = parse_ini_file('.env');
use BushlanovDev\MaxMessengerBot\Api;

$api = new Api($env['TOKEN']);

$dispatcher = $api->getUpdateDispatcher();

$dispatcher->onMessageCreated(function (MessageCreatedUpdate $update, Api $api) {

    if (!empty($update->message->body->attachments)) {
        foreach ($update->message->body->attachments as $attachment) {
            $url = $attachment['payload']['url'];
        }
    }

    $api->sendMessage(
        $update->message->recipient->userId,
        $update->message->recipient->chatId,
        $update->message->body->text
    );
});

// или
//$dispatcher->addHandler(UpdateType::MessageCreated, function (BotStartedUpdate $update, Api $api) {
//    $api->sendMessage(
//        userId: $update->chatId,
//        text: 'Я запущен!',
//    );
//});

$handler = $api->createWebhookHandler('super_secret');
$handler->handle();
