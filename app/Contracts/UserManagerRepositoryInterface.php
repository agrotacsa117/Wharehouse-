<?php

namespace App\Contracts;

use App\Models\User;

interface UserManagerRepositoryInterface
{

    public function saveUser(User $user): User;

    public function deleteUser(User $user): bool;

    public function updateUser(User $user): ?User;

    public function deleteUserById(int $id): bool;

    public function deleteUserByEmail(string $email): bool;
}
