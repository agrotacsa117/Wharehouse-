<?php

namespace App\Contracts;
use App\Models\User;

interface UserServiceInterface {


    public function createUser(User $user): User;

    public function deleteUser(User $user): bool;

    public function updateUser(User $user): User;

    public function saerchById(int $id): ?User;

    public function deleteUserById(int $id): bool;


    
}

