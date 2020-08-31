<?php
namespace Atomic\Client;

use Ramsey\Uuid\Uuid;

/**
 * Class AtomicEvent
 *
 * Events are data payloads sent to the Atomic Platform and used to create cards for the specified end users.
 *
 * @package Atomic\Models
 */
class AtomicEvent
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lifecycleId;

    /**
     * @var AtomicEventPayload
     */
    private $payload;

    /**
     * AtomicEvent constructor.
     * @param string $name
     * @param AtomicEventPayload $payload
     * @param string|null $lifecycleId
     * @throws \Exception
     */
    public function __construct(string $name, AtomicEventPayload $payload, ?string $lifecycleId = null)
    {
        $this->setName($name);
        $this->setPayload($payload);
        $this->setLifecycleId($lifecycleId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return AtomicEvent
     */
    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLifecycleId(): ?string
    {
        return $this->lifecycleId;
    }

    /**
     * @param string|null $lifecycleId UUID4
     * @return AtomicEvent
     * @throws \Exception
     */
    private function setLifecycleId(?string $lifecycleId = null)
    {
        $this->lifecycleId = $lifecycleId ?? Uuid::uuid4()->toString();

        return $this;
    }

    /**
     * @return AtomicEventPayload
     */
    public function getPayload(): AtomicEventPayload
    {
        return $this->payload;
    }

    /**
     * @param AtomicEventPayload $payload
     * @return AtomicEvent
     */
    private function setPayload(AtomicEventPayload $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name'        => $this->getName(),
            'lifecycleId' => $this->getLifecycleId(),
            'payload'     => $this->getPayload()->toArray(),
        ];
    }

    /**
     * Get the model as JSON for the API payload.
     * @return string
     */
    public function toJson(): string
    {
        $event = $this->toArray();

        return json_encode(['events' => [$event]], JSON_UNESCAPED_SLASHES);
    }
}
