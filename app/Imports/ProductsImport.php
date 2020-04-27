<?php

namespace App\Imports;

use App\Category;
use App\Produit;
use \Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    private $import_id;
    public function __construct($import_id)
    {
        $this->import_id = $import_id;
    }

    public function collection(Collection $rows)
    {
        unset($rows[0]);
        //dd($rows);
        foreach ($rows as $row) 
        {
            if($row[0] && is_null($row[1]) && is_null($row[2]) && is_null($row[3])){
                $category = Category::create([
                    'libelle' => $row[0],
                    'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $row[0])))
                ]);
            }else{
                Produit::create([
                    'libelle' => $row[0],
                    'origin' => $row[1],
                    'unit_price' => $row[2],
                    'unit' => $row[3],
                    'category_id' => $category->id,
                    'import_id' => $this->import_id
                ]);
            }
        }
    }
}
