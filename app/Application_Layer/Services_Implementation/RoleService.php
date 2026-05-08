<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\RoleServiceI;
use App\Contracts\RoleRepositoryI;
use App\Mappers\DTO\RoleDTO;

class RoleService implements RoleServiceI
{
    private RoleRepositoryI $roleRepository;

    public function __construct(
        RoleRepositoryI $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    public function getRolesList(): array
    {
        $rols = $this->roleRepository->getAllRoles();

        for ($i = 0; $i < count($rols); $i++) {
            $rol = $rols[$i];
            $rols[$i] = new RoleDTO(
                $rol['id'],
                $rol['name']
            );
        }

        return  $rols;
    }
}
