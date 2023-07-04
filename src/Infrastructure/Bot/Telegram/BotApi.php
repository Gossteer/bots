<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Infrastructure\Bot\Contract\MessengerSenderInterface;
use TelegramBot\Api\Botan;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidJsonException;

class BotApi extends \TelegramBot\Api\BotApi implements MessengerSenderInterface
{
    public function __construct($token, $trackerToken = null, int $curlTimeout = 25)
    {
        parent::__construct($token, $trackerToken);
        $this->setCurlOption(CURLOPT_TIMEOUT, $curlTimeout);
    }

    /**
     * Установка ссылки на обработчик с секретным ключом
     *
     * @param string|\CURLFile|null $certificate
     * @throws Exception
     * @throws HttpException
     * @throws InvalidJsonException
     * @see self::setWebhook()
     */
    public function setWebhookWithToken(
        string $url = '',
        string|\CURLFile|null $certificate = null,
        ?string $secretToken = null
    ): bool {
        return (bool)$this->call('setWebhook', [
            'url' => $url,
            'certificate' => $certificate,
            'secret_token' => $secretToken,
        ]);
    }

    public function send(string $method, array $data): array|bool
    {
        return $this->call(
            $method,
            $data
        );
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param int $timeout
     * @return Update[]
     * @throws Exception
     * @throws HttpException
     * @throws InvalidJsonException
     */
    public function getUpdates($offset = 0, $limit = 100, $timeout = 0): array
    {
        $updates = ArrayOfUpdates::fromResponse($this->call('getUpdates', [
            'offset' => $offset,
            'limit' => $limit,
            'timeout' => $timeout,
        ]));

        if ($this->tracker instanceof Botan) {
            foreach ($updates as $update) {
                $this->trackUpdate($update);
            }
        }

        return $updates;
    }
}
