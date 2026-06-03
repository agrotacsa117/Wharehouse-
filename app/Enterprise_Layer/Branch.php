<?php

namespace App\Enterprise_Layer;

class Branch
{
    private ?int $id;

    private string $branchName;

    /**
     * Constructor de la Entidad Enterprise.
     */
    public function __construct(?int $id, string $branchName)
    {
        $this->id = $id;
        $this->branchName = $branchName;
    }

    /**
     * Retorna el ID de la sucursal.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retorna el nombre de la sucursal.
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }
}
