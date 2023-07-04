<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramBot;
use App\Infrastructure\Encryption\EncryptionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TelegramBotCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    public function __construct(
        private readonly EncryptionInterface $encryption
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return TelegramBot::class;
    }

    protected function otherConfigureFields(): array
    {
        return [
            TextField::new(
                'name',
                'Наименование бота'
            )->setRequired(true),
            BooleanField::new(
                'isActive',
                'Активен?'
            ),
            TextField::new(
                'token',
                'Токен выдаваемый BotFather'
            )
                ->setRequired(true)
                ->onlyWhenCreating(),
            AssociationField::new(
                'telegramBotGroup',
                'Flow бота'
            )->setRequired(true),
            TextField::new(
                'secretToken',
                'Секретный ключ, регистрируемый через api telegram'
            )->setRequired(true),
            TextField::new(
                'environment',
                'Среда (dev,stage,prod, пусто - для всех)'
            ),
        ];
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['encryptValue'],
        ];
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\Encryption\EncryptException
     */
    public function encryptValue(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof TelegramBot)) {
            return;
        }
        $secretToken = $entity->getSecretToken();
        $id = md5($secretToken);
        $entity->setId($id)
            ->setToken($this->encryption->encrypt($entity->getToken()))
            ->setSecretToken($this->encryption->encrypt($secretToken));
    }

    public function createEntity(string $entityFqcn): object
    {
        /** @var TelegramBot $entity */
        $entity = parent::createEntity($entityFqcn);
        $entity->setSecretToken(uuid_create(UUID_TYPE_RANDOM));
        return $entity;
    }
}
