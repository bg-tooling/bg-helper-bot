#!/usr/bin/env php
<?php

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Psr\Log\LoggerInterface;
use Sufir\Infr\Queue\QueueProducer;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

$container = require 'config/container.php';

/** @var Telegram $telegram */
$telegram = $container->get(Telegram::class);
$telegram->useGetUpdatesWithoutDatabase();

$logger = $container->get(LoggerInterface::class);

/** @var QueueProducer $queue */
$queue = $container->get(QueueProducer::class);

/** @var \Cycle\Database\Database $db */
//$db = $container->get('db-core');

while (true) {
    try {
        $updates = $telegram->handleGetUpdates([
            'allowed_updates' => [Update::TYPE_MESSAGE],
            'timeout' => 60
        ]);
        /*$updates = Request::getUpdates([
            'allowed_updates' => [Update::TYPE_MESSAGE],
            'timeout' => 90
        ]);*/

        if (is_iterable($updates->getResult())) {
            /** @var Update $update */
            foreach ($updates->getResult() as $update) {
                if ($update->getUpdateType() !== 'message') {
                    $logger->info('Update type is:' . $update->getUpdateType());
                    continue;
                }

                $message = $update->getMessage();

                if (
                    $message->getText(true) === null
                    || trim($message->getText(true)) === ''
                    || !in_array($message->getType(), ['text', 'command', 'bot_command'])
                ) {
                    $logger->debug($message->getText(true));
                    $logger->info('Message type is:' . $message->getType());
                    continue;
                }

                $queue->push($message);
            }
        }
    } catch (Exception $e) {
        $logger->critical($e->getMessage(), ['exception' => $e]);
    }

    $logger->debug('Im waiting updates...');
}
