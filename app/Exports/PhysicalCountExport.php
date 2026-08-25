<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhysicalCountExport implements FromArray, WithHeadings
{
    private array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return array_map('array_values', $this->rows);
    }

    public function headings(): array
    {
        return ['Producto', 'Bodega', 'Ubicación', 'Descripción', 'Sistema', 'Conteo', 'Diferencia'];
    }
}
