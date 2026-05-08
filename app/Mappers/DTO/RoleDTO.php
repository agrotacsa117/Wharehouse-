<?php

namespace App\Mappers\DTO;

class RoleDTO
{
    private int $id;
    private string $roleName;

    public function __construct(
        int $id,
        string $roleName
    ) {
        $this->id = $id;
        $this->roleName = $roleName;
    }
    // Getter para id
    public function getId(): int
    {
        return $this->id;
    }

    // Setter para id
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter para roleName
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    // Setter para roleName
    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }
}
