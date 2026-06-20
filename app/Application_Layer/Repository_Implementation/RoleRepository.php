<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\RoleRepositoryI;
use App\Models\Rol;

class RoleRepository implements RoleRepositoryI
{
    public function getAllRoles(): array
    {
        $rols = Rol::select('id', 'name')->get();

        return $rols->toArray();
    }
}
