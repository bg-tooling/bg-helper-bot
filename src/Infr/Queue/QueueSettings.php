<?php

declare(strict_types=1);

namespace Sufir\Infr\Queue;

final readonly class QueueSettings
{
    public function __construct(
        public string $host,
        public int $port
    ) {
    }
}
