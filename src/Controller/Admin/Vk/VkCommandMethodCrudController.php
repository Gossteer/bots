<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkMessengerMethod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VkCommandMethodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VkMessengerMethod::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['name']);
    }

    protected function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
        ];
    }
}
