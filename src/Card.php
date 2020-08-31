<?php
namespace Atomic;

/**
 * Class Card
 * The Card API exposes information about generated card instances, and allows them to be actioned programmatically.
 * Card instances can be queried by any combination of the following attributes:
 *
 * eventName
 * lifecycleId
 * cardTemplateId
 * userId
 * status
 *
 * @package Atomic
 * @link https://documentation.atomic.io/api/card-api?id=retrieving-cards
 */
class Card
{
    /**
     * @var string
     */
    private $eventName;

    /**
     * @var string
     */
    private $eventSource;

    /**
     * @var array
     */
    private $definition;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var string
     */
    private $created;

    /**
     * @var string
     */
    private $updated;

    /**
     * @var string
     */
    private $lifeCycleId;

    /**
     * @var int
     */
    private $cardTemplateId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $status;

    /**
     * Card constructor.
     * @param string $lifeCycleId
     */
    public function __construct(string $lifeCycleId)
    {
        $this->setLifeCycleId($lifeCycleId);
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     * @return Card
     */
    private function setEventName(string $eventName): Card
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventSource(): string
    {
        return $this->eventSource;
    }

    /**
     * @param string $eventSource
     * @return Card
     */
    private function setEventSource(string $eventSource): Card
    {
        $this->eventSource = $eventSource;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        return $this->definition;
    }

    /**
     * @param array $definition
     * @return Card
     */
    private function setDefinition(array $definition): Card
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     * @return Card
     */
    private function setPayload(array $payload): Card
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     * @return Card
     */
    private function setCreated(string $created): Card
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     * @return Card
     */
    private function setUpdated(string $updated): Card
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getLifeCycleId(): string
    {
        return $this->lifeCycleId;
    }

    /**
     * @param string $lifeCycleId
     * @return Card
     */
    private function setLifeCycleId(string $lifeCycleId): Card
    {
        $this->lifeCycleId = $lifeCycleId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCardTemplateId(): int
    {
        return $this->cardTemplateId;
    }

    /**
     * @param int $cardTemplateId
     * @return Card
     */
    private function setCardTemplateId(int $cardTemplateId): Card
    {
        $this->cardTemplateId = $cardTemplateId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return Card
     */
    private function setUserId(string $userId): Card
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Card
     */
    private function setStatus(string $status): Card
    {
        $this->status = $status;

        return $this;
    }
}
