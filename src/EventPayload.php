<?php
namespace Atomic;

/**
 * Class EventPayload
 * @package Atomic
 */
class EventPayload
{
    /**
     * @var EventMetadata
     */
    private $metadata;

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
     * @param EventMetadata $metadata
     * @param array|string[] $detail
     * @param array|string[] $target A list of user ids to target
     */
    public function __construct(EventMetadata $metadata, array $detail, array $target)
    {
        $this->setDetail($detail);
        $this->setMetadata($metadata);
        $this->setTarget($target);
    }

    /**
     * @return EventMetadata
     */
    public function getMetadata(): EventMetadata
    {
        return $this->metadata;
    }

    /**
     * @param EventMetadata $metadata
     * @return EventPayload
     */
    private function setMetadata(EventMetadata $metadata): EventPayload
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
        $this->target = $target;

        return $this;
    }
}
