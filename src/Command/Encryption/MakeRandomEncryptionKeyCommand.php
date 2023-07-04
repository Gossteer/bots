<?php

declare(strict_types=1);

namespace App\Command\Encryption;

use App\Infrastructure\Encryption\EncryptionInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'encryption:generate-key',
    description: 'Генерация случайного ключа шифрования'
)]
class MakeRandomEncryptionKeyCommand extends Command
{
    public function __construct(
        private readonly EncryptionInterface $encryption
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            'Ваш код шифрования: ' . $this->encryption->createNewRandomKey()
        );

        return Command::SUCCESS;
    }
}
