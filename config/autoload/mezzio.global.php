<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;

$env = getenv();

return [
    // Toggle the configuration cache. Set this to boolean false, or remove the
    // directive, to disable configuration caching. Toggling development mode
    // will also disable it by default; clear the configuration cache using
    // `composer clear-config-cache`.
    ConfigAggregator::ENABLE_CACHE => true,

    // Enable debugging; typically used to provide debugging information within templates.
    'debug'  => false,
    'mezzio' => [
        // Provide templates for the error handling middleware to use when
        // generating responses.
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
    'appHost' => $env['APP_HOST'] ?? null,
    'botApiKey' => $env['BOT_KEY'] ?? null,
    'botUsername' => $env['BOT_NAME'] ?? null,
    'botAdminId' => $env['BOT_ADMIN_ID'] ?? null,
    'botId' => $env['BOT_ID'] ?? null,
    'secret' => $env['SECRET'] ?? null,
    'timezone' => new DateTimeZone((getenv('TZ') === false) ? 'Europe/Moscow' : getenv('TZ')),
    'db' => [
        'core' => getenv('DB_CORE'),
    ],
    'queue' => new \Sufir\Infr\Queue\QueueSettings(
        host: getenv('QUEUE_HOST'),
        port: (int) getenv('QUEUE_PORT'),
    ),
];
