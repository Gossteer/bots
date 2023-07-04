<?php

declare(strict_types=1);

namespace App\Command\Bot\Telegram;

use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Telegram\BotApi;
use App\Infrastructure\Bot\Telegram\TelegramUpdate;
use App\Infrastructure\Encryption\EncryptionInterface;
use App\Operation\Message\MessengerUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidJsonException;

#[AsCommand(
    name: 'tg:get-update',
    description: 'Запуск бота в режиме активного (sleep(4)) прослушивания. Не будет работать, если к боту уже подключен webhook'
)]
class TelegramGetUpdateCommand extends TelegramBaseCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const CONNECTION_TIMED_OUT = 2;
    private const MAX_COUNT_ERROR = 50;
    private const EXECUTE_CURLOPT_TIMEOUT = 30;

    public function __construct(
        protected readonly MessageBusInterface $bus,
        EncryptionInterface $encryption,
        EntityManagerInterface $defaultEntityManager,
        BotGetterInterface $telegramBotGetter,
    ) {
        parent::__construct(
            $encryption,
            $defaultEntityManager,
            $telegramBotGetter,
        );
    }

    /**
     * @throws BotNotFoundException
     * @throws DecryptException
     * @throws Exception
     * @throws HttpException
     * @throws InvalidJsonException
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $secretToken = (string)$input->getOption(self::OPTION_SECRET_TOKEN);
        $errorCounter = 0;
        /** @phpstan-ignore-next-line */
        while (true) {
            // Делаем запрос по ботам, чтобы не приходилось полностью перезагружать команду, при добавлении нового бота
            $this->defaultEntityManager->clear();
            if ($secretToken) {
                $telegramBots = [
                    $this->telegramBotGetter->getByEncryptSecretToken($secretToken),
                ];
            } else {
                $telegramBots = $this->telegramBotGetter->getAllBots();
            }
            foreach ($telegramBots as $telegramBot) {
                try {
                    $updatesAll = [];
                    $offset = 0;
                    $botApi = new BotApi(
                        $this->encryption->decrypt($telegramBot->getToken())
                    );
                    $botApi->setCurlOption(CURLOPT_TIMEOUT, self::EXECUTE_CURLOPT_TIMEOUT);
                    while (true) {
                        $updates = $botApi->getUpdates($offset, timeout: self::CONNECTION_TIMED_OUT);
                        if (!$updates) {
                            break;
                        }

                        $offset = end($updates)->getUpdateId() + 1;
                        $updatesAll = array_merge($updatesAll, $updates);
                    }

                    $output->writeln(
                        sprintf('Собрано %d сообщений', count($updatesAll))
                    );
                    foreach ($updatesAll as $update) {
                        $this->bus->dispatch(
                            new MessengerUpdate(new TelegramUpdate($update, clone $telegramBot))
                        );
                    }
                } catch (\Throwable $th) {
                    if ($errorCounter++ >= self::MAX_COUNT_ERROR) {
                        throw $th;
                    } else {
                        $this->logger?->error($th->getMessage(), [
                            'exception' => $th,
                            'errorCounter' => $errorCounter,
                        ]);
                    }
                }
                sleep(4);
            }
        }
    }

    protected function getDescriptionToSecretToken(): string
    {
        return 'Зашифрованный секретный токен бота, для его идентификации у нас в системе. Если не передан, запускаем по всем ботам';
    }
}
