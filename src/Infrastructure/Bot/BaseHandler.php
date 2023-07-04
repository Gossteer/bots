<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot;

use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Domain\SharedCore\Exception\Bot\MessengerUserException;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Contract\MessengerCheckUserInterface;
use App\Infrastructure\Bot\Contract\MessengerCommandGetterInterface;
use App\Infrastructure\Bot\Contract\MessengerMessagesDispatcherInterface;
use App\Infrastructure\Bot\Contract\MessengerMessagesGetterInterface;
use App\Infrastructure\Bot\Contract\MessengerTemplateEngineInterface;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;

abstract class BaseHandler implements Contract\MessengerHandlerInterface
{
    public function __construct(
        protected readonly MessengerCommandGetterInterface $messengerCommandGetter,
        protected readonly MessengerMessagesGetterInterface $messengerMessagesGetter,
        protected readonly MessengerCheckUserInterface $messengerCheckUser,
        protected readonly MessengerMessagesDispatcherInterface $messengerMessagesDispatcher,
        protected readonly MessengerTemplateEngineInterface $templateEngine,
        protected readonly BotGetterInterface $botGetter,
    ) {
    }

    public function handle(MessengerUpdateInterface $messengerUpdate): void
    {
        if (!$this->messengerCheckUser->checkUser($messengerUpdate->getMessengerUserDto())) {
            throw new MessengerUserException();
        }

        $command = $this->messengerCommandGetter->getCommand($messengerUpdate);
        if (!$command) {
            throw new CommandNotFoundException();
        }
        $this->botGetter->getBotByDtoAndCommand(
            $messengerUpdate->getBotDto(),
            $command
        );

        if ($this->messengerMessagesGetter->neededSkipWaitingMessages($command, $messengerUpdate->getBotDto())) {
            $this->messengerMessagesDispatcher->skipNoMustMessage(
                $messengerUpdate->getMessengerUserDto(),
                $messengerUpdate->getBotDto()
            );
        }

        $this->templateEngine->setMessengerUpdate($messengerUpdate);
        $this->templateEngine->setMessengerMessagesDto(
            $this->messengerMessagesGetter->getMessages(
                $command,
                $messengerUpdate->getBotDto()
            )
        );

        $this->messengerMessagesDispatcher->dispatchMessages(
            $this->templateEngine->applyTemplate(),
            $messengerUpdate->getMessengerUserDto(),
            $messengerUpdate->getBotDto()
        );
    }
}
