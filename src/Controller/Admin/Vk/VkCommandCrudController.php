<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkCommand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VkCommandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VkCommand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['name', 'vkBotGroup.name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
            BooleanField::new('isActive'),
            BooleanField::new('isNeededSkipWaitMessages'),
            AssociationField::new('vkBotGroup'),
        ];
    }
}
