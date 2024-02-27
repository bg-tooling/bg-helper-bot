<?php

declare(strict_types=1);

namespace Sufir\Helper\Command;

use Cycle\Database\Database;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

final readonly class SearchTermCommand
{
    public function __construct(private Database $database, private int $botId)
    {
    }

    public function __invoke(Message $message): ?ServerResponse
    {
        $messageText = $message->getText(true);

        if ($messageText === null) {
            return Request::sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'reply_to_message_id' => $message->getMessageId(),
                'text'    => 'Я вас не понял...',
            ]);
        }

        $query = str_ireplace(
            ['или', 'не '],
            ['OR', '-'],
            $messageText
        );

        $results = $this->findTerms($query);

        if (count($results) === 0) {
           $result = Request::sendMessage([
                    'chat_id' => $message->getChat()->getId(),
                    'reply_to_message_id' => $message->getMessageId(),
                    'text'    => 'К сожалению я не смог ничего найти по вашему запросу... 😦 Проверьте нет ли опечаток в вашем сообщении.',
            ]);
        } else {
            $answer = $this->buildAnswer($results);
            $result = Request::sendMessage([
                    'chat_id' => $message->getChat()->getId(),
                    'reply_to_message_id' => $message->getMessageId(),
                    'parse_mode' => 'Markdown',
                    'text'    => $answer,
            ]);
        }

        return $result;
    }

    public function buildAnswer(array $results): string
    {
        $answer = '';
        for ($i = 0; $i < count($results); $i++) {
            $result = $results[$i];

            if ($i === 0) {
                $answer .= sprintf("*%s* \n%s", $result['title'], $result['body']);

                if (count($results) > 1) {
                    $variants = [
                        "\n\nТак же может быть полезно:\n",
                        "\n\nЕщё я кое-что знаю про:\n",
                        "\n\nВозможно будет полезно что-то из:\n",
                        "\n\nЕщё может пригодиться:\n",
                        "\n\nТакже можете спросить меня о:\n",
                    ];
                    $answer .= $variants[array_rand($variants)];
                }
            } else {
                $answer .= sprintf("• `%s`", $result['title']);
            }
        }

        return $answer . "\n\nВаш [Помощник](tg://user?id={$this->botId})";
    }

    private function findTerms(string $query): array
    {
        return $this->database
            ->query(
                'SELECT *, ts_rank_cd(tsv, query) AS rank '
                . 'FROM core.article, websearch_to_tsquery(\'pg_catalog.russian\', :q) AS query '
                . 'WHERE query @@ tsv '
                . 'ORDER BY rank ASC '
                . 'LIMIT 5',
                [':q' => $query]
            )
            ->fetchAll();
    }
}