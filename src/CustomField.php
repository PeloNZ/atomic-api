<?php
declare(strict_types=1);

namespace Atomic;

/**
 * @link https://documentation.atomic.io/api/users-api
 * Custom field associated with a user profile record
 */
abstract class CustomField
{
    /**
     * @var string[]
     * @uses static::type
     */
    const ALLOWED_TYPES = [
        'text',
        'date',
    ];

    abstract public function name(): string;
    abstract public function label(): string;
    abstract public function type(): string;

    public function validatedType(): string
    {
        $type = $this->type();
        in_array($type, self::ALLOWED_TYPES) || throw new \UnexpectedValueException(
            "type is '$type', should be one of the allowed ones: (" . json_encode(self::ALLOWED_TYPES) . ')'
        );

        return $type;
    }
}