<?php

namespace App\Contracts;

interface ProductRepositoryInterface
{
    public function findAll(): array;

    public function findNameById(string $id): string;
}
