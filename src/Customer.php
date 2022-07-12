<?php
declare(strict_types=1);

namespace Atomic;

use JsonSerializable;

class Customer implements JsonSerializable
{
    public const CUSTOM_FIELDS = [
        'farm_id',
        'user_id',
        'farm_name',
        'farm_code',
        'region',
        'country',
        'product_version',
        'product',
        'user_role',
        'user_access',
        'farm_type',
        'production_type',
    ];

    public function __construct(
             private string $id,
             private int $farmId,
             private int $userId,
             private ?string $farmName,
             private ?string $farmCode,
             private ?string $region,
             private ?string $country,
             private ?string $productVersion,
             private ?string $product,
             private ?string $userName,
             private ?string $userRole,
             private ?string $userAccess,
             private ?string $farmType,
             private ?string $productionType,
    ) {
    }

    public function asUser(): array
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->farmId,
            'user_id' => $this->userId,
            'farm_name' => $this->farmName,
            'farm_code' => $this->farmCode,
            'region' => $this->region,
            'country' => $this->country,
            'product_version' => $this->productVersion,
            'product' => $this->product,
            'user_role' => $this->userRole,
            'user_access' => $this->userAccess,
            'farm_type' => $this->farmType,
            'production_type' => $this->productionType,
            'profile' => [
                'name' => $this->userName,
//                'email' => "string", // not in use
//                'phone' => "string", // not in use
            ],
            'preferences' => [
                'notificationsEnabled' => true,
            ],
        ];
    }

    /** {@inheritDoc} */
    public function jsonSerialize()
    {
        return $this->asUser();
    }
}