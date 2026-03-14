<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\ProductRepositoryInterface;
use App\Models\ProductModel;

class ProductRepositoryImplementation implements ProductRepositoryInterface
{
    public function findAll(): array
    {
        return ProductModel::select(
            'ItemCode',
            'ItemName'
        )->get()->toArray();
    }

    public function findNameById(string $id): string
    {
        $productName = ProductModel::where(
            'ItemCode',
            $id
        )->value('ItemName');

        return $productName;
    }
}
