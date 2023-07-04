<?php

declare(strict_types=1);

namespace App\Command\Encryption;

use App\Domain\SharedCore\Exception\Encryption\EncryptException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'encryption:encrypt',
    description: 'Шифрование'
)]
class EncryptCommand extends BaseEncryptionCommand
{
    /**
     * @throws EncryptException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            'Зашифрованное значение: ' . $this->encryption->encrypt($this->getText($input))
        );
        return Command::SUCCESS;
    }
}
