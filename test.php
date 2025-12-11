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

$subscriptions = $api->getSubscriptions();

foreach ($subscriptions as $subscription)
{
    $api->unsubscribe( $subscription->url);
}

$api->subscribe(
    $env['WEBHOOK_URL'] . '/test_webhook.php', 'super_secret',
    [
        // Типы хуков которые вы хотите получать (либо ничего не указывать, чтобы получать все)
        UpdateType::BotStarted,
        UpdateType::MessageCreated,
        UpdateType::BotStopped,
    ]
);
$subscriptions = $api->getSubscriptions();
print_r($subscriptions);

$botInfo = $api->getBotInfo();

print_r($botInfo);

$api->sendMessage($env['USER_ID'], null, 'Привет!', [
new InlineKeyboardAttachmentRequest([
    [new CallbackButton('Нажми меня!', 'payload_button1')],
    [new LinkButton('Нет, лучше Нажми меня!', 'https://tallanto.com')],
]),
], MessageFormat::Html);
