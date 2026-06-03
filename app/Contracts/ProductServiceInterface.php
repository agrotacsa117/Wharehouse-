<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;

interface ProductServiceInterface
{
    public function listAllProducts(): array;

    public function getProductNameById(string $id): ResultPattern;
}
