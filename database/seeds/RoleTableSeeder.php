<?php

use App\Role;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'libelle' => 'Utilisateur',
            'slug' => 'user',
            'level' => 1
        ]);

        Role::create([
            'libelle' => 'Opérateur',
            'slug' => 'operator',
            'level' => 2
        ]);

        Role::create([
            'libelle' => 'Administrateur',
            'slug' => 'administrator',
            'level' => 3
        ]);

    }
}
