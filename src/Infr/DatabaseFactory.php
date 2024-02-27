<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;

final class DatabaseFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        $parts = explode('-', $requestedName, 2);
        if ($parts[0] !== 'db') {
            return false;
        }

        /** @var DatabaseConfig $dbConfig */
        $dbConfig = $container->get(DatabaseConfig::class);

        return $dbConfig->hasDatabase($parts[1] ?? '');
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DatabaseInterface
    {
        $parts = explode('-', $requestedName, 2);
        if ($parts[0] !== 'db' || ! isset($parts[1]) || $parts[1] === '') {
            throw new InvalidServiceException("{$requestedName} not found in container");
        }

        /** @var DatabaseManager $dbal */
        $dbal = $container->get(DatabaseManager::class);
        return $dbal->database($parts[1]);
    }
}