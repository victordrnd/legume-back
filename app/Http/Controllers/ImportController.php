<?php

namespace App\Http\Controllers;

use App\Http\Requests\Imports\ImportRequest;
use App\Import;
use App\Imports\SheetsImport;
use Exception;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(ImportRequest $req){
        $import = Import::create([
            'from' => $req->from,
            'to' => $req->to
        ]);
        try{
            \Excel::import(new SheetsImport($import->id), request()->file('file'));
        }catch(Exception $e){
            return response()->json(['error' => "Une erreur est présente dans le fichier, vérifier qu'aucune case ne soit vide"]);
        }
        return Import::where('id', $import->id)->with('products', 'paniers')->first();
    }
}
