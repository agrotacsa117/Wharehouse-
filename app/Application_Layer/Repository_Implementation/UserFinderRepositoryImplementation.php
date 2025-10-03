<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\UserFinderRepositoryInterface;
use App\Models\User;

class UserFinderRepositoryImplementation implements UserFinderRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByUsername(string $username): ?User
    {
        return User::where('name', $username)->first();
    }

    public function findAllUsers(): array
    {
        return User::all()->toArray();
    }

    public function sortUsersAsc(string $field): array
    {
        return User::orderBy($field, 'asc')->get()->toArray();
    }

    public function sortUsersDesc(string $field): array
    {
        return User::orderBy($field, 'desc')->get()->toArray();
    }

    public function returnFirstRecord(string $field): ?User
    {
        return User::orderBy($field, 'asc')->first();
    }
}
