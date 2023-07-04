<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Amqp;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpFactory as SymfonyAmqpFactory;

class AmqpFactory extends SymfonyAmqpFactory
{
    /**
     * @var array<string, \AMQPConnection>
     */
    private array $connectionPool = [];
    private int $openedChannels = 0;

    /**
     * @param array<mixed> $credentials
     * @throws \JsonException
     */
    public function createConnection(array $credentials): \AMQPConnection
    {
        $connectionId = md5(json_encode($credentials, JSON_THROW_ON_ERROR));

        if (!isset($this->connectionPool[$connectionId])) {
            $connection = new \AMQPConnection($credentials);
            $this->connectionPool[$connectionId] = $connection;
        }

        return $this->connectionPool[$connectionId];
    }

    public function createChannel(\AMQPConnection $connection): \AMQPChannel
    {
        ++$this->openedChannels;

        return parent::createChannel($connection);
    }

    /**
     * @return array<string, \AMQPConnection>
     */
    public function getConnectionPool(): array
    {
        return $this->connectionPool;
    }

    public function getOpenedChannelsCount(): int
    {
        return $this->openedChannels;
    }
}
