<?php

namespace App\Contracts;

use App\Models\User;

interface UserFinderRepositoryInterface
{

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function findByUsername(string $username): ?User;

    /**
     * @return User[]
     */
    public function findAllUsers(): array;

    /**
     * @return User[]
     */
    public function sortUsersAsc(string $field): array;

    /**
     * @return User[]
     */
    public function sortUsersDesc(string $field): array;

    public function returnFirstRecord(string $field): ?User;
}
