<?php

declare(strict_types=1);

namespace App\Command\Bot\Telegram;

use App\Domain\SharedCore\Exception\Encryption\EncryptException;
use App\Entity\Telegram\TelegramBot;
use App\Entity\Telegram\TelegramBotGroup;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Encryption\EncryptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'tg:create-bot',
    description: 'Создает бота по вашему токену и привязывает ко всем командам'
)]
class MakeTelegramBotCommand extends TelegramBaseCommand
{
    public const OPTION_TOKEN = 'token';
    public const OPTION_GROUP_ID = 'group_id';

    public function __construct(
        protected readonly string $appDeployment,
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

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            self::OPTION_TOKEN,
            't',
            InputOption::VALUE_REQUIRED,
            'Телеграмм токен, выданный @BotFather'
        );

        $this->addOption(
            self::OPTION_GROUP_ID,
            'g',
            InputOption::VALUE_OPTIONAL,
            'Группа телеграмм бота, если не указана, будет выбрана случайно'
        );
    }

    /**
     * @throws EncryptException
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = (string)$input->getOption(self::OPTION_TOKEN);
        $secretToken = $input->getOption(self::OPTION_SECRET_TOKEN) ?? uuid_create(UUID_TYPE_RANDOM);
        $group = $input->getOption(self::OPTION_GROUP_ID) ? $this->defaultEntityManager->find(
            TelegramBotGroup::class,
            $input->getOption(self::OPTION_GROUP_ID)
        ) : null;
        if (!$group) {
            $groups = $this->defaultEntityManager->getRepository(TelegramBotGroup::class)->findAll();
            $group = $groups ? $groups[array_rand($groups)] : throw new \Exception('Нет ни одной группы');
        }

        $telegramBot = new TelegramBot();
        $telegramBot->setId(md5($secretToken));
        $telegramBot->setName(uuid_create(UUID_TYPE_RANDOM));
        $telegramBot->setEnvironment($this->appDeployment);
        $telegramBot->setIsActive(true);
        $telegramBot->setTelegramBotGroup($group);
        $telegramBot->setToken(
            $this->encryption->encrypt($token)
        );
        $telegramBot->setSecretToken(
            $this->encryption->encrypt($secretToken)
        );
        $this->defaultEntityManager->persist($telegramBot);
        $this->defaultEntityManager->flush();

        $output->writeln('Bot id: ' . $telegramBot->getId());

        return Command::SUCCESS;
    }

    protected function getDescriptionToSecretToken(): string
    {
        return 'Расшифрованный секретный токен бота, для его идентификации у нас в системе. 
        Если не передан, генерируем сами.';
    }
}
