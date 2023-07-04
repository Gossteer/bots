<?php

declare(strict_types=1);

namespace App\Command\Encryption;

use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'encryption:decrypt',
    description: 'Дешифрование'
)]
class DecryptCommand extends BaseEncryptionCommand
{
    /**
     * @throws DecryptException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            'Расшифрованное значение: ' . $this->encryption->decrypt($this->getText($input))
        );
        return Command::SUCCESS;
    }
}
