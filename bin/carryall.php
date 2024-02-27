<?php

declare(strict_types=1);

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;
use Psr\Container\ContainerInterface;
use Spiral\RoadRunner\Jobs\Consumer;
use Sufir\Helper\Command\SearchTermCommand;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require 'config/container.php';

/** @var SearchTermCommand $queue */
$searchTerm = $container->get(SearchTermCommand::class);

/** @var Telegram $telegram */
$telegram = $container->get(Telegram::class);

$maxAttempts = 2;

    while ($task = (new Consumer())->waitTask()) {
        try {
            $message = new Message(json_decode($task->getPayload(), true, JSON_THROW_ON_ERROR));
            $searchTerm($message);
            $task->complete();
        } catch (Throwable $e) {
            $attempts = ($task->hasHeader('attempts')) ? (int) $task->getHeaderLine('attempts') : 0;
            $task = $task->withHeader('attempts', (string) ++$attempts);

            if ($attempts >= $maxAttempts) {
                $task->fail(sprintf('%s: %s', $e::class, $e->getMessage()));
            } else {
                $task
                    ->withDelay(5)
                    ->fail(sprintf('%s: %s', $e::class, $e->getMessage()), requeue: true);
            }
        }
    }
