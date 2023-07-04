<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramCommand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TelegramCommandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TelegramCommand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['name', 'telegramBotGroup.name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
            BooleanField::new('isActive'),
            BooleanField::new('isNeededSkipWaitMessages'),
            AssociationField::new('telegramBotGroup'),
        ];
    }
}
