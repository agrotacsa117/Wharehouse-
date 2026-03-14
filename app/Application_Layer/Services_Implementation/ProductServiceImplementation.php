<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\Mappers\DTO\ProductListDTO;

class ProductServiceImplementation implements ProductServiceInterface
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function listAllProducts(): array
    {
        $products = $this->productRepository->findAll();

        for ($i = 0; $i < count($products) ; $i++) {
            $products[$i] = new ProductListDTO(
                $products[$i]['ItemCode'],
                $products[$i]['ItemName']
            );
        }

        return $products;
    }

    public function getProductNameById(string $id): ResultPattern
    {
        $productName = $this->productRepository->findNameById($id);
        return ResultPattern::success($productName);
    }
}
