<?php

declare(strict_types=1);

namespace Sufir\Infr;

use Longman\TelegramBot\Telegram;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;

final class TelegramFactory
{
    public function __invoke(ContainerInterface $container): Telegram
    {
        $config = $container->get('config');

        $telegram = new Telegram($config['botApiKey'], $config['botUsername']);

        if ($config['botAdminId'] !== null) {
            $telegram->enableAdmin((int) $config['botAdminId']);
        }

        /*$telegram->setUpdateFilter(static function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {

            $msg = $update->getMessage()->getText(without_cmd: true);

            if (!str_contains($msg, $botUsername)) {
                return false;
            }

            $user_id = $update->getMessage()->getFrom()->getId();
            if ($user_id === 428) {
                return true;
            }

            $reason = "Invalid user with ID {$user_id}";
            return false;
        });*/

        return $telegram;
    }
}
