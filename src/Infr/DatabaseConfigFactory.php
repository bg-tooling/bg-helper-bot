<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\Config\Postgres\DsnConnectionConfig;
use Cycle\Database\Config\PostgresDriverConfig;
use DateTimeZone;
use Psr\Container\ContainerInterface;
use RuntimeException;

final class DatabaseConfigFactory
{
    public function __invoke(ContainerInterface $container): DatabaseConfig
    {
        /** @var array{debug: bool, timezone: DateTimeZone, db: array<non-empty-string, non-empty-string|false>} $appConfig */
        $appConfig = $container->get('config');

        $timezoneName = ($appConfig['timezone']->getName() !== '')
            ? $appConfig['timezone']->getName()
            : 'Europe/Moscow';

        $connections = $databases = [];
        foreach ($appConfig['db'] as $dbAlias => $dbDsn) {
            if ($dbDsn === false) {
                throw new RuntimeException("Database config not set fot: '{$dbAlias}'");
            }

            $databases[$dbAlias] = [
                'connection' => $dbAlias,
            ];

            $connections[$dbAlias] = new PostgresDriverConfig(
                connection: new DsnConnectionConfig($dbDsn),
                timezone: $timezoneName,
                queryCache: true,
                readonlySchema: true,
            );
        }

        return new DatabaseConfig([
            //'default' => 'core',
            'databases' => $databases,
            'connections' => $connections,
        ]);
    }
}