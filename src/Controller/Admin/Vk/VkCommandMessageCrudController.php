<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkCommandMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class VkCommandMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VkCommandMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['vkMessage.data', 'vkCommand.name', 'vkCommand.vkBotGroup.name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            BooleanField::new('mustWait'),
            IntegerField::new('sortOrder'),
            IntegerField::new('waitSeconds'),
            AssociationField::new('vkCommand'),
            AssociationField::new('vkMessage'),
        ];
    }
}
