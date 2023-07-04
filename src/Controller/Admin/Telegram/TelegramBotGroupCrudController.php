<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramBotGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TelegramBotGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TelegramBotGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
            BooleanField::new('isActive'),
        ];
    }
}
