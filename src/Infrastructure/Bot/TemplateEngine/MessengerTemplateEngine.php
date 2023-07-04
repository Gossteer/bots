<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\TemplateEngine;

use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Contract\MessengerTemplateEngineInterface;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;
use App\Infrastructure\Bot\Dto\MessageDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;

abstract class MessengerTemplateEngine implements MessengerTemplateEngineInterface
{
    protected MessengerUpdateInterface $messengerUpdate;
    /**
     * @var MessengerMessageDto[]
     */
    protected array $messengerMessagesDto;

    public function setMessengerUpdate(MessengerUpdateInterface $messengerUpdate): void
    {
        $this->messengerUpdate = $messengerUpdate;
    }

    public function setMessengerMessagesDto(array $messengerMessagesDto): void
    {
        $this->messengerMessagesDto = $messengerMessagesDto;
    }

    public function applyTemplate(): array
    {
        $messengerMessagesDtoWithTemplate = [];
        foreach ($this->messengerMessagesDto as $messengerMessageDto) {
            $messageDto = $messengerMessageDto->getMessageDto();
            $data = $this->handle($messageDto->getData());
            $messengerMessagesDtoWithTemplate[] = new MessengerMessageDto(
                new MessageDto(
                    $messageDto->getMessageId(),
                    $messageDto->getMethod(),
                    $data
                ),
                $messengerMessageDto->getMessengerTypeEnum(),
                $messengerMessageDto->isMustWait(),
                $messengerMessageDto->getWaitSeconds()
            );
        }
        return $messengerMessagesDtoWithTemplate;
    }

    /**
     * @param mixed[] $data
     * @return mixed[]
     * @throws NotDefineMessageMessengerException
     */
    protected function handle(array $data): array
    {
        return json_decode(
            $this->strReplace(json_encode($data)),
            true
        );
    }

    /**
     * @return mixed[]
     */
    abstract protected function searchReplace(): array;

    /**
     * @return mixed[]
     * @throws NotDefineMessageMessengerException
     */
    abstract protected function dataReplace(): array;

    /**
     * @throws NotDefineMessageMessengerException
     */
    protected function strReplace(string $stringData): string
    {
        return str_replace(
            $this->searchReplace(),
            $this->dataReplace(),
            $stringData
        );
    }
}
