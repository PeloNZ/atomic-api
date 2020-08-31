<?php
namespace Atomic;

/**
 * Class EventPayload
 * @package Atomic
 */
class EventPayload
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
     * EventPayload constructor.
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
     * @return EventPayload
     */
    private function setMetadata(array $metadata): EventPayload
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
     * @return EventPayload
     */
    private function setDetail(array $detail): EventPayload
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
     * @return EventPayload
     */
    private function setTarget(array $target): EventPayload
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
