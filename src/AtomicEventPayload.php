<?php
namespace Atomic\Client;

/**
 * Class AtomicEventPayload
 * @package Atomic\Client
 */
class AtomicEventPayload
{
    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var array
     */
    private $detail = [];

    /**
     * @var array
     */
    private $target = [];

    /**
     * AtomicEventPayload constructor.
     * @param array|string[] $metadata
     * @param array|string[] $detail
     * @param array|string[] $target A list of user ids to target
     */
    public function __construct(array $metadata, array $detail, array $target)
    {
        $this->setDetail($detail);
        $this->setMetadata($metadata);
        $this->setTarget($target);
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     * @return AtomicEventPayload
     */
    private function setMetadata(array $metadata): AtomicEventPayload
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return array
     */
    public function getDetail(): array
    {
        return $this->detail;
    }

    /**
     * @param array $detail
     * @return AtomicEventPayload
     */
    private function setDetail(array $detail): AtomicEventPayload
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @return array
     */
    public function getTarget(): array
    {
        return $this->target;
    }

    /**
     * @param array $target User IDs that will see the event.
     * @return AtomicEventPayload
     */
    private function setTarget(array $target): AtomicEventPayload
    {
        $this->target = [
            'usersById' => $target,
        ];

        return $this;
    }

    public function toArray(): array
    {
        return [
            'metadata' => $this->metadata,
            'detail'   => $this->detail,
            'target'   => $this->target,
        ];
    }
}
