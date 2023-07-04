<?php

declare(strict_types=1);

namespace App\Command\Bot\Telegram;

use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use App\Infrastructure\Bot\Telegram\BotApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\InvalidJsonException;

#[AsCommand(
    name: 'tg:set-webhook',
    description: 'Установить/удалить webhook из telegram'
)]
class TelegramSetWebhookCommand extends TelegramBaseCommand
{
    public const WEBHOOK_OPTION_NAME = 'telegram_webhook';

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            self::WEBHOOK_OPTION_NAME,
            'w',
            InputOption::VALUE_OPTIONAL,
            'Ссылка на которую telegram будет отправлять новые сообщения. Для удаления не указывать этот параметр'
        );
    }

    /**
     * @throws DecryptException
     * @throws Exception
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws InvalidJsonException
     * @throws BotNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webhookUrl = (string)$input->getOption(self::WEBHOOK_OPTION_NAME);
        $secretToken = (string)$input->getOption(self::OPTION_SECRET_TOKEN);

        $telegramBot = $this->telegramBotGetter->getByEncryptSecretToken($secretToken);
        $botApi = new BotApi(
            $this->encryption->decrypt($telegramBot->getToken())
        );
        $botApi->setWebhookWithToken(
            $webhookUrl,
            secretToken: $this->encryption->decrypt($telegramBot->getSecretToken())
        );
        $output->writeln('Info: ' . $botApi->getWebhookInfo()->toJson());

        return Command::SUCCESS;
    }

    protected function getDescriptionToSecretToken(): string
    {
        return 'Зашифрованный секретный токен бота, для его идентификации у нас в системе';
    }
}
