<?php

namespace App\Imports;

use App\Category;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetsImport implements WithMultipleSheets
{

    private $import_id;
    public function __construct($import_id){
        $this->import_id = $import_id;
    }


    public function sheets(): array
    {
        $category = Category::create([
            'libelle' => 'Panier',
            'slug' => 'panier'
        ]);
        return [
            0 => new ProductsImport($this->import_id),
            1 => new PanierImport($this->import_id),
            2 => new PanierImport($this->import_id),
            3 => new PanierImport($this->import_id)
        ];
    }
}
