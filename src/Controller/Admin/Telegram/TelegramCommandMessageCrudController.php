<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramCommandMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class TelegramCommandMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TelegramCommandMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['telegramMessage.data', 'telegramCommand.name', 'telegramCommand.telegramBotGroup.name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            BooleanField::new('mustWait'),
            IntegerField::new('sortOrder'),
            IntegerField::new('waitSeconds'),
            AssociationField::new('telegramCommand'),
            AssociationField::new('telegramMessage'),
        ];
    }
}
