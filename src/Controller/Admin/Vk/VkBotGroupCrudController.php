<?php

declare(strict_types=1);

namespace App\Controller\Admin\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Vk\VkBotGroup;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VkBotGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VkBotGroup::class;
    }

    public function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
            BooleanField::new('isActive'),
        ];
    }
}
