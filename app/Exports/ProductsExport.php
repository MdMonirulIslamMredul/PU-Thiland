<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $products;

    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function map($product): array
    {
        return [
            $product->title,
            $product->slug,
            $product->category?->name,
            $product->subcategory?->name,
            $product->price,
            $product->open_price,
            $product->quantity,
            $product->unit_type,
            $product->unit_name,
            $product->grade,
            $product->status ? 'Active' : 'Inactive',
        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Slug',
            'Category',
            'Subcategory',
            'Price',
            'Open Price',
            'Quantity',
            'Unit Type',
            'Unit Name',
            'Grade',
            'Status',
        ];
    }
}
