<?php

declare(strict_types=1);

namespace App\Controller\Admin\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Entity\Telegram\TelegramMessengerMethod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TelegramMessengerMethodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TelegramMessengerMethod::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['name']);
    }

    public function otherConfigureFields(): array
    {
        return [
            TextField::new('name'),
        ];
    }
}
