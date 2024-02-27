<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseManager;
use Longman\TelegramBot\Telegram;
use Psr\Log\LoggerInterface;
use Sufir\Infr\Queue\QueueProducer;
use Sufir\Infr\Queue\QueueProducerFactory;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories'  => [
                    Telegram::class => TelegramFactory::class,
                    LoggerInterface::class => LoggerFactory::class,
                    DatabaseConfig::class => DatabaseConfigFactory::class,
                    DatabaseManager::class => DatabaseManagerFactory::class,
                    QueueProducer::class => QueueProducerFactory::class,
                ],
                'abstract_factories' => [
                    DatabaseFactory::class,
                ],
            ],
        ];
    }
}
