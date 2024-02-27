<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseManager;
use Psr\Container\ContainerInterface;

final class DatabaseManagerFactory
{
    public function __invoke(ContainerInterface $container): DatabaseManager
    {
        $dbConfig = $container->get(DatabaseConfig::class);

        return new DatabaseManager($dbConfig);
    }
}