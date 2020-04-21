<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\ProductAvailabilityRequest;
use App\Import;
use App\Produit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function getAllProducts(ProductAvailabilityRequest $req){
        $date = Carbon::parse($req->date) ?? Carbon::now();
        try{
            $import = Import::where('from', '<=', $date)->where('to', '>=', $date)->firstOrFail();
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => "Aucun produit n'est disponible pour la période demandée. Réessayez plus tard"], 422);
        }
        $products = Produit::where('import_id', $import->id)->orderBy('category_id')->with('category')->get();
        return $products;
    }
}
