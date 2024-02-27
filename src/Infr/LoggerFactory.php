<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

final class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        /** @var array{debug: bool} & array<string, mixed> $appConfig */
        $appConfig = $container->get('config');
        $isDebug = $appConfig['debug'];

        $log = new Logger('logger-default');
        $log->pushHandler(new StreamHandler(
            stream: 'php://stderr',
            level: ($isDebug) ? Level::Debug : Level::Info
        ));

        return $log;
    }
}