<?php

declare(strict_types=1);

namespace App\Command\Bot\Vk;

use App\Entity\Vk\VkBot;
use App\Infrastructure\Encryption\EncryptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vk:create-bot',
    description: 'Создает бота по вашим настройкам сообщества вконтакте'
)]
class MakeVkBotCommand extends Command
{
    protected const ERROR_NOT_HAVE_GROUPS = 'Нет ни одной группы';

    public function __construct(
        protected readonly EncryptionInterface $encryption,
        protected readonly EntityManagerInterface $defaultEntityManager,
    ) {
        parent::__construct();
    }

    public const OPTION_ACCESS_KEY = 'access_key';
    public const OPTION_GROUP_ID = 'group_id';
    public const OPTION_SECRET_KEY = 'secret_key';
    public const OPTION_CONFIRMATION_TOKEN = 'confirmation_token';
    public const OPTION_FLOW = 'flow';
    public const OPTION_NAME = 'name';

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            self::OPTION_ACCESS_KEY,
            't',
            InputOption::VALUE_REQUIRED,
            'Ключ доступа, сгенерированный при настройке сообщества'
        );

        $this->addOption(
            self::OPTION_GROUP_ID,
            'g',
            InputOption::VALUE_REQUIRED,
            'Идентификатор сообщества вконтакте'
        );

        $this->addOption(
            self::OPTION_FLOW,
            'f',
            InputOption::VALUE_OPTIONAL,
            'Номер группы команд приложения (если не задан будет выбран рандомно)'
        );

        $this->addOption(
            self::OPTION_NAME,
            'm',
            InputOption::VALUE_OPTIONAL,
            'Наименование бота'
        );

        $this->addOption(
            self::OPTION_SECRET_KEY,
            's',
            InputOption::VALUE_OPTIONAL,
            'Секретный ключ для идентификации обращения Callback API (если не передать - генерируется автоматически)'
        );

        $this->addOption(
            self::OPTION_CONFIRMATION_TOKEN,
            'c',
            InputOption::VALUE_OPTIONAL,
            'Ответ для подтверждения адреса Callback API (передать при необходимости)'
        );
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\Encryption\EncryptException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accessKey = (string)$input->getOption(self::OPTION_ACCESS_KEY);
        $groupId = (int)$input->getOption(self::OPTION_GROUP_ID);
        $flow = $input->getOption(self::OPTION_FLOW) ? $this->defaultEntityManager->find(
            VkBot::class,
            $input->getOption(self::OPTION_FLOW)
        ) : null;
        if (!$flow) {
            $groups = $this->defaultEntityManager->getRepository(VkBot::class)->findAll();
            $flow = $groups ? $groups[array_rand($groups)]->getGroupId() : throw new \Exception(self::ERROR_NOT_HAVE_GROUPS);
        }

        $secretKey = $input->getOption(self::OPTION_SECRET_KEY) ?? md5(uuid_create(UUID_TYPE_RANDOM));
        $name = $input->getOption(self::OPTION_NAME) ?? uuid_create(UUID_TYPE_RANDOM);
        $confirmationToken = $input->getOption(self::OPTION_CONFIRMATION_TOKEN);

        $vkBot = new VkBot();
        $vkBot->setId(md5($accessKey))
            ->setAccessKey($this->encryption->encrypt($accessKey))
            ->setGroupId($groupId)
            ->setBotGroupId($flow)
            ->setSecretToken($secretKey)
            ->setConfirmationToken($confirmationToken)
            ->setName($name);

        $this->defaultEntityManager->persist($vkBot);
        $this->defaultEntityManager->flush();

        $output->writeln('Bot id: ' . $vkBot->getId());

        return Command::SUCCESS;
    }
}
