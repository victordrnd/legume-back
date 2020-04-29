<?php

namespace App\Imports;

use App\LignePanier;
use App\Panier;
use App\Produit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PanierImport implements ToCollection
{

    private $import_id;
    public function __construct($import_id)
    {
        $this->import_id = $import_id;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $panier = Panier::create([
            'import_id' => $this->import_id,
            'libelle' => $collection[0][9],
            'price' => $collection[1][9]
        ]);
        //dd($collection);
        unset($collection[0]);
        foreach($collection as $row){
            if($row[4] > 0){
                try{
                    $produit = Produit::where('libelle', $row[0])->orderBy('created_at', 'DESC')->firstOrFail();
                }catch(ModelNotFoundException $e){
                    continue;
                }
                LignePanier::create([
                    'panier_id' => $panier->id,
                    'produit_id' => $produit->id,
                    'quantity' => $row[4]
                ]);
            }
        }
    }
}
