<?php

require __DIR__.'/vendor/autoload.php';

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Enums\MessageFormat;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\InlineKeyboardAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;

$env = parse_ini_file('.env');

use BushlanovDev\MaxMessengerBot\Api;

$api = new Api($env['TOKEN']);

//$api->unsubscribe($env['WEBHOOK_URL'] . '/webhook.php');

$subscriptions = $api->getSubscriptions();
print_r($subscriptions);

$api->subscribe(
    url: $env['WEBHOOK_URL'] . '/test_webhook.php', // URL на который будут приходить хуки
    secret: 'super_secret',             // Секретная фраза для проверки хуков
    updateTypes: [
    // Типы хуков которые вы хотите получать (либо ничего не указывать, чтобы получать все)
    UpdateType::BotStarted,
    UpdateType::MessageCreated,
    UpdateType::BotStopped,
],
);

$botInfo = $api->getBotInfo();

print_r($botInfo);

$api->sendMessage(
    userId: $env['USER_ID'],     // ID пользователя получателя сообщения
    text: 'Привет!', // Текст сообщения, вы можете использовать HTML или Markdown
    attachments: [
    new InlineKeyboardAttachmentRequest([
        [new CallbackButton('Нажми меня!', 'payload_button1')],
        [new LinkButton('Нет, лучше Нажми меня!', 'https://example.com')],
    ]),
],
    format: MessageFormat::Html, // Формат сообщения (Markdown или HTML)
);
