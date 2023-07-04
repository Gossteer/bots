<?php

declare(strict_types=1);

namespace App\Command\Bot\Vk;

use App\Entity\Vk\VkBot;
use App\Infrastructure\Bot\Vk\CallbackApiHandler;
use App\Infrastructure\Bot\Vk\VkBotGetter;
use App\Infrastructure\Encryption\EncryptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use VK\CallbackApi\LongPoll\VKCallbackApiLongPollExecutor;
use VK\Client\VKApiClient;

#[AsCommand(
    name: 'vk:get-update',
    description: 'VK LongPull'
)]
class VkGetUpdateCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const MAX_COUNT_ERROR = 50;
    public const LISTEN_WAIT = 25;

    public function __construct(
        protected readonly MessageBusInterface $bus,
        protected readonly EntityManagerInterface $defaultEntityManager,
        protected readonly EncryptionInterface $encryption,
        protected readonly VkBotGetter $vkBotGetter
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errorCounter = 0;
        $botsTs = [];
        while (true) { // @phpstan-ignore-line
            // Делаем запрос по ботам, чтобы не приходилось полностью перезагружать команду, при добавлении нового бота
            $this->defaultEntityManager->clear();
            foreach ($this->vkBotGetter->getAllBots() as $vkBot) {
                try {
                    $output->writeln($vkBot->getBotName());
                    /** @var VkBot|null $bot */
                    $bot = $this->defaultEntityManager->find(VkBot::class, $vkBot->getId());
                    if ($bot) {
                        $handler = new CallbackApiHandler($this->bus, $vkBot);
                        $executor = new VKCallbackApiLongPollExecutor(
                            new VKApiClient(),
                            $this->encryption->decrypt($vkBot->getToken()),
                            $bot->getGroupId(),
                            $handler,
                            self::LISTEN_WAIT
                        );
                        $botsTs[$bot->getId()] = (int)$executor->listen($botsTs[$bot->getId()] ?? null);
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
            }
        }
    }
}
