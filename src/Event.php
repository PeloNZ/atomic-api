<?php
namespace Atomic;

use Exception;
use Ramsey\Uuid\Uuid;

/**
 * Class Event
 *
 * Events are data payloads sent to the Atomic Platform and used to create cards for the specified end users.
 *
 * @package Atomic\Models
 */
class Event
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
     * @var EventPayload
     */
    private $payload;

    /**
     * Event constructor.
     * @param string $name
     * @param EventPayload $payload
     * @param string|null $lifecycleId
     * @throws Exception
     */
    public function __construct(string $name, EventPayload $payload, ?string $lifecycleId = null)
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
     * @return Event
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
     * @return Event
     * @throws Exception
     */
    private function setLifecycleId(?string $lifecycleId = null)
    {
        $this->lifecycleId = $lifecycleId ?? Uuid::uuid4()->toString();

        return $this;
    }

    /**
     * @return EventPayload
     */
    public function getPayload(): EventPayload
    {
        return $this->payload;
    }

    /**
     * @param EventPayload $payload
     * @return Event
     */
    private function setPayload(EventPayload $payload)
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
            'payload'     => $this->getPayload(),
        ];
    }

    /**
     * Get the event as JSON for the API payload.
     * @return string
     */
    public function toJson(): string
    {
        return json_encode(['events' => [[
            'name'        => $this->getName(),
            'lifecycleId' => $this->getLifecycleId(),
            'payload'     => [
                'metadata' => $this->getPayload()->getMetadata()->toArray(),
                'detail' => $this->getPayload()->getDetail(),
                'target' => [
                    'usersById' => array_values($this->getPayload()->getTarget())
                ]
            ]
        ]]], JSON_UNESCAPED_SLASHES);
    }
}
