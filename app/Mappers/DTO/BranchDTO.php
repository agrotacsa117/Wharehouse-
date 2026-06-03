<?php

namespace App\Mappers\DTO;

class BranchDTO
{
    // Las propiedades se definen como tipadas (PHP 7.4)
    private ?int $id;

    private string $branchName;

    /**
     * Constructor del DTO.
     * Transporta los datos de forma estricta.
     */
    public function __construct(?int $id, string $branchName)
    {
        $this->id = $id;
        $this->branchName = $branchName;
    }

    /**
     * Obtiene el ID. Puede ser nulo si es un DTO para creación (Request).
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre de la sucursal.
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }
}
