<?php

declare(strict_types=1);

namespace Sufir\Helper;

use Sufir\Helper\Command\SearchTermCommand;
use Sufir\Helper\Command\SearchTermCommandFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                ],
                'factories'  => [
                    SearchTermCommand::class => SearchTermCommandFactory::class,
                ],
            ],
        ];
    }
}
