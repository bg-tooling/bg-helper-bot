<?php

declare(strict_types=1);

namespace Sufir\Infr\Queue;

use Psr\Container\ContainerInterface;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Jobs\Jobs;

final class QueueProducerFactory
{
    public function __invoke(ContainerInterface $container): QueueProducer
    {
        /** @var array{debug: bool, queue: QueueSettings} $config */
        $config = $container->get('config');
        $settings = $config['queue'];

        $jobs = new Jobs(
            RPC::create(sprintf('tcp://%s:%d', $settings->host, $settings->port))
        );

        return new QueueProducer(
            jobs: $jobs,
            queueName: 'updates',
        );
    }
}
