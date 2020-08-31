<?php
namespace Atomic\Client;

/**
 * Class AtomicCard
 * The Card API exposes information about generated card instances, and allows them to be actioned programmatically.
 * Card instances can be queried by any combination of the following attributes:
 *
 * eventName
 * lifecycleId
 * cardTemplateId
 * userId
 * status
 *
 * @package Atomic\Client
 * @link https://documentation.atomic.io/api/card-api?id=retrieving-cards
 */
class AtomicCard
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
     * AtomicCard constructor.
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
     * @return AtomicCard
     */
    private function setEventName(string $eventName): AtomicCard
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
     * @return AtomicCard
     */
    private function setEventSource(string $eventSource): AtomicCard
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
     * @return AtomicCard
     */
    private function setDefinition(array $definition): AtomicCard
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
     * @return AtomicCard
     */
    private function setPayload(array $payload): AtomicCard
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
     * @return AtomicCard
     */
    private function setCreated(string $created): AtomicCard
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
     * @return AtomicCard
     */
    private function setUpdated(string $updated): AtomicCard
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
     * @return AtomicCard
     */
    private function setLifeCycleId(string $lifeCycleId): AtomicCard
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
     * @return AtomicCard
     */
    private function setCardTemplateId(int $cardTemplateId): AtomicCard
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
     * @return AtomicCard
     */
    private function setUserId(string $userId): AtomicCard
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
     * @return AtomicCard
     */
    private function setStatus(string $status): AtomicCard
    {
        $this->status = $status;

        return $this;
    }
}
