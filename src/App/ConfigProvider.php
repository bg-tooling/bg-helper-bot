<?php

declare(strict_types=1);

namespace Sufir\App;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                ],
                'factories'  => [
                ],
            ],
        ];
    }
}
