<?php

declare(strict_types=1);

namespace App\Command\Encryption;

use App\Infrastructure\Encryption\EncryptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class BaseEncryptionCommand extends Command
{
    protected const ENCRYPTION_OPTION_TEXT = 'text';

    public function __construct(
        protected readonly EncryptionInterface $encryption
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::ENCRYPTION_OPTION_TEXT,
            't',
            InputOption::VALUE_REQUIRED,
            'Текст для работы (де)шифрования'
        );
    }

    protected function getText(InputInterface $input): string
    {
        return (string)$input->getOption(self::ENCRYPTION_OPTION_TEXT);
    }
}
