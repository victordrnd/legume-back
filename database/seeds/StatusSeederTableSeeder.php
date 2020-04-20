<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Status;
class StatusSeederTableSeeder extends Seeder
{
    public function run()
    {
        Status::create([
            'libelle' => 'En attente de la commande',
            'slug' => 'waiting'
        ]);

        Status::create([
            'libelle' => 'Confirmée',
            'slug' => 'confirmed'
        ]);

        Status::create([
            'libelle' => 'En préparation',
            'slug' => 'preparation'
        ]);

        Status::create([
            'libelle' => 'Annulée',
            'slug' => 'canceled'
        ]);

        Status::create([
            'libelle' => 'Terminée',
            'slug' => 'finished'
        ]);
    }
}
