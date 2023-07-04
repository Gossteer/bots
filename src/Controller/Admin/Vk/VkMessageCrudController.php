<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkMessage;
use App\Infrastructure\EasyAdmin\Type\JsonCodeEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;

class VkMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VkMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Сообщение ВКонтакте')
            ->setSearchFields(['data', 'vkMessengerMethod.name']);
    }

    public function createEntity(string $entityFqcn): object
    {
        /** @var \App\Entity\Vk\VkMessage $entity */
        $entity = parent::createEntity($entityFqcn);
        $entity->setData([
            'message' => 'text',
        ]);
        return $entity;
    }

    protected function otherConfigureFields(): array
    {
        return [
            CodeEditorField::new('data')
                ->setFormType(JsonCodeEditorType::class)
                ->formatValue(
                    fn ($object) => json_encode($object, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE)
                )->setSortable(false)
                ->setLanguage('js'),
            AssociationField::new('vkMessengerMethod', 'Method VK'),
        ];
    }
}
