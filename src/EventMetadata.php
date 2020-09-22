<?php


namespace Atomic;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Exception;

/**
 * Class EventMetadata
 * @package Atomic
 */
class EventMetadata
{
    /**
     * @var string
     */
    private $embargo;
    /**
     * @var string
     */
    private $expires;
    /**
     * @var string
     */
    private $expiresInterval;
    /**
     * @var bool
     */
    private $overwrite = false;

    /**
     * EventMetadata constructor.
     * @param string|null $embargo
     * @param string|null $expires
     * @param string|null $expiresInterval
     * @param bool $overwrite
     * @throws Exception
     */
    public function __construct(?string $embargo, ?string $expires, ?string $expiresInterval, bool $overwrite)
    {
        $this->setEmbargo($embargo);
        $this->setExpires($expires);
        $this->setExpiresInterval($expiresInterval);
        $this->setOverwrite($overwrite);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'embargo' => $this->getEmbargo(),
            'expires' => $this->getExpires(),
            'expires_interval' => $this->getExpiresInterval(),
            'overwrite' => $this->getOverwrite(),
        ]);
    }

    /**
     * @return string
     */
    public function getEmbargo(): string
    {
        return $this->embargo;
    }

    /**
     * @param string|null $embargo
     * @return EventMetadata
     * @throws Exception
     */
    public function setEmbargo(?string $embargo): EventMetadata
    {
        if (!empty($embargo)) {
            $this->validateDateTimeAtom($embargo);
        }
        $this->embargo = $embargo;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpires(): string
    {
        return $this->expires;
    }

    /**
     * @param string|null $expires
     * @return EventMetadata
     * @throws Exception
     */
    public function setExpires(?string $expires): EventMetadata
    {
        if (!empty($expires)) {
            $this->validateDateTimeAtom($expires);
        }
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiresInterval(): ?string
    {
        return $this->expiresInterval;
    }

    /**
     * @param string|null $expiresInterval
     * @return EventMetadata
     * @throws Exception
     */
    public function setExpiresInterval(?string $expiresInterval): EventMetadata
    {
        if (!empty($expiresInterval)) {
            $this->validateDateInterval($expiresInterval);
        }
        $this->expiresInterval = $expiresInterval;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * @param bool $overwrite
     * @return EventMetadata
     */
    public function setOverwrite(bool $overwrite): EventMetadata
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * The API expects a valid ISO-8601 datetime string.
     * @param string $dateTime
     * @throws Exception
     * @return void
     */
    private function validateDateTimeAtom(string $dateTime): void
    {
        if (DateTime::createFromFormat(DateTimeInterface::ATOM, $dateTime) === false) {
            throw new Exception('Invalid datetime string: '
                . implode(', ', DateTime::getLastErrors()['errors'])
            );
        }
    }

    /**
     * The API expects a valid ISO-8601 duration string.
     * @param string $dateInterval
     * @throws Exception
     * @return void
     */
    private function validateDateInterval(string $dateInterval): void
    {
        try {
            new DateInterval($dateInterval);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}