<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\ProductAvailabilityRequest;
use App\Http\Resources\ImportResource;
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
            $import = Import::where('from', '<=', $date)->where('to', '>', $date)->orderBy('created_at', 'desc')->with('products.category','paniers.products.product.category')->firstOrFail();
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => "Aucun produit n'est disponible pour la période demandée. Réessayez plus tard"], 422);
        }
        return ImportResource::make($import);
    }
}
