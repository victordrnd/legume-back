<?php

use Illuminate\Database\Seeder;
use App\Import;
use App\Category;
use App\Produit;
use App\Status;
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
        $this->call(RoleTableSeeder::class);
    }
}
