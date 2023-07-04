<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkBot;
use App\Infrastructure\Encryption\EncryptionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VkBotCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    public function __construct(
        private readonly EncryptionInterface $encryption
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return VkBot::class;
    }

    protected function otherConfigureFields(): array
    {
        return [
            TextField::new(
                'name',
                'Наименование бота'
            ),
            BooleanField::new(
                'isActive',
                'Активен?'
            ),
            TextField::new(
                'accessKey',
                'Ключ доступа (генерируется в сообществе)'
            )
                ->setRequired(true)
                ->hideWhenUpdating()
                ->hideOnIndex(),
            IntegerField::new(
                'groupId',
                'Идентификатор сообщества'
            )
                ->setRequired(true)
                ->hideWhenUpdating(),
            AssociationField::new(
                'vkBotGroup',
                'Flow бота'
            )->hideWhenUpdating(),
            TextField::new(
                'secretToken',
                'secret для Callback'
            ),
            TextField::new(
                'confirmationToken',
                'Строка подтверждения'
            ),
            TextField::new(
                'environment',
                'Среда (dev,stage,prod, пусто - для всех)'
            )->hideWhenUpdating()->hideOnIndex(),
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
        if (!($entity instanceof VkBot)) {
            return;
        }
        $id = md5($entity->getAccessKey());
        $accessKey = $this->encryption->encrypt($entity->getAccessKey());
        $secretToken = $entity->getSecretToken();
        if (empty($secretToken)) {
            $secretToken = md5(uuid_create(UUID_TYPE_RANDOM));
        }
        $entity->setId($id)
            ->setAccessKey($accessKey)
            ->setSecretToken($secretToken)
            ->setConfirmationToken($entity->getConfirmationToken());
    }
}
