<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramMessage;
use App\Infrastructure\EasyAdmin\Type\JsonCodeEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;

class TelegramMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TelegramMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['data', 'telegramMessengerMethod.name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            CodeEditorField::new('data')
                ->setFormType(JsonCodeEditorType::class)
                ->formatValue(
                    fn ($object) => json_encode($object, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE)
                )->setSortable(false)
                ->setLanguage('js'),
            AssociationField::new('telegramMessengerMethod', 'Method'),
        ];
    }

    public function createEntity(string $entityFqcn): object
    {
        /** @var TelegramMessage $entity */
        $entity = parent::createEntity($entityFqcn);
        $entity->setData([
            'example' => 'text',
        ]);
        return $entity;
    }
}
