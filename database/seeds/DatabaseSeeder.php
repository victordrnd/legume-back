<?php

use Illuminate\Database\Seeder;
use App\Import;
use App\Category;
use App\Produit;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusSeederTableSeeder::class);

        Import::create([
            'from' => '2020-04-01',
            'to' => '2020-06-01'
        ]);
        Category::create([
            'libelle' => 'Nos paniers',
            'slug' => 'panier'
        ]);
        Produit::create([
            'libelle' => 'Panier gourmand',
            'origin' => 'France',
            'unit_price' => '25',
            'unit' => '€',
            'category_id' => 1,
            'import_id' => 1
        ]);
    }
}
