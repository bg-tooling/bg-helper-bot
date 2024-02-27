<?php

declare(strict_types=1);

namespace Sufir\Infr\Queue;

use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\QueueInterface;

final readonly class QueueProducer
{
    private QueueInterface $queue;

    public function __construct(
        private Jobs $jobs,
        private string $queueName
    ) {
        $this->queue = $this->jobs->connect(
            (strlen($this->queueName) !== 0) ? $this->queueName : 'updates'
        );
    }

    /**
     * @return string Registered task identity
     * @throws \Spiral\RoadRunner\Jobs\Exception\JobsException
     */
    public function push(object $task): string
    {
        $queuedTask = $this->queue->create($task::class, json_encode($task, JSON_THROW_ON_ERROR));
        $queuedTask = $this->queue->dispatch($queuedTask);

        return $queuedTask->getId();
    }
}
