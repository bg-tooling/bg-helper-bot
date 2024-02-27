<?php

declare(strict_types=1);

namespace Sufir\Helper\Command;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

final class SearchTermCommandFactory
{
    public function __invoke(ContainerInterface $container): SearchTermCommand
    {
        /** @var \Cycle\Database\Database $db */
        $db = $container->get('db-core');
        $appConfig = $container->get('config');

        return new SearchTermCommand($db, (int) $appConfig['botId']);
    }
}