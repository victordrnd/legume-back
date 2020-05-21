<?php

namespace App\Http\Controllers;

use App\Http\Requests\Imports\DeleteImportRequest;
use App\Http\Requests\Imports\ImportRequest;
use App\Import;
use App\Imports\SheetsImport;
use App\Produit;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ImportController extends Controller
{

    public function getAll(){
        return Import::orderBy('from', 'desc')->get();
    }
    public function import(ImportRequest $req){
        //self::deletePreviousImport();
        $import = Import::create([
            'from' => $req->from,
            'to' => $req->to
        ]);
        try{
            \DB::beginTransaction();
            \Excel::import(new SheetsImport($import->id), request()->file('file'));
            \DB::commit();
        }catch(Exception $e){
            \DB::rollBack();
            return response()->json(['error' => "Une erreur est présente dans le fichier, vérifier qu'aucune case ne soit vide"]);
        }
        return Import::where('id', $import->id)->with('products', 'paniers')->first();
    }


    public function deleteImport(DeleteImportRequest $req){
        Import::find($req->id)->delete();

        return response()->json(['success' => 'true']);
    }


    public static function deletePreviousImport(){
        $imports = Import::where('to','<=', Carbon::now()->subDays(1))->get();
        foreach($imports as $import) {
            $import->delete();
        } 
    }
}
