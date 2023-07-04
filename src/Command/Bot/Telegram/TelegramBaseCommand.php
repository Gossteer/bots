<?php

declare(strict_types=1);

namespace App\Command\Bot\Telegram;

use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Encryption\EncryptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

abstract class TelegramBaseCommand extends Command
{
    public const OPTION_SECRET_TOKEN = 'secret_token';

    public function __construct(
        protected readonly EncryptionInterface $encryption,
        protected readonly EntityManagerInterface $defaultEntityManager,
        protected readonly BotGetterInterface $telegramBotGetter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_SECRET_TOKEN,
            's',
            InputOption::VALUE_OPTIONAL,
            $this->getDescriptionToSecretToken()
        );
    }

    abstract protected function getDescriptionToSecretToken(): string;
}
