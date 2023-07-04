<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Telegram\TelegramBot;
use App\Entity\Telegram\TelegramBotGroup;
use App\Entity\Telegram\TelegramCommand;
use App\Entity\Telegram\TelegramCommandMessage;
use App\Entity\Telegram\TelegramMessage;
use App\Entity\Telegram\TelegramMessengerMethod;
use App\Entity\Vk\VkBot;
use App\Entity\Vk\VkBotGroup;
use App\Entity\Vk\VkCommand;
use App\Entity\Vk\VkCommandMessage;
use App\Entity\Vk\VkMessage;
use App\Entity\Vk\VkMessengerMethod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        //         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //         return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        //         if ('jane' === $this->getUser()->getUsername()) {
        //             return $this->redirect('...');
        //         }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return parent::configureDashboard()
            ->setTitle('Bot admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Telegram');
        yield MenuItem::linkToCrud('Боты', 'fas fa-list', TelegramBot::class);
        yield MenuItem::linkToCrud('Группы ботов', 'fa fa-list', TelegramBotGroup::class);
        yield MenuItem::linkToCrud('Методы сообщений', 'fas fa-list', TelegramMessengerMethod::class);
        yield MenuItem::linkToCrud('Сообщения', 'fa fa-list', TelegramMessage::class);
        yield MenuItem::linkToCrud('Команды', 'fas fa-list', TelegramCommand::class);
        yield MenuItem::linkToCrud('Команды<->сообщения', 'fas fa-list', TelegramCommandMessage::class);
        yield MenuItem::section('ВКонтакте');
        yield MenuItem::linkToCrud('Боты', 'fas fa-list', VkBot::class);
        yield MenuItem::linkToCrud('Группы ботов', 'fas fa-list', VkBotGroup::class);
        yield MenuItem::linkToCrud('Методы сообщений', 'fas fa-list', VkMessengerMethod::class);
        yield MenuItem::linkToCrud('Сообщения', 'fas fa-list', VkMessage::class);
        yield MenuItem::linkToCrud('Команды', 'fas fa-list', VkCommand::class);
        yield MenuItem::linkToCrud('Команды<->сообщения', 'fas fa-list', VkCommandMessage::class);
    }
}
