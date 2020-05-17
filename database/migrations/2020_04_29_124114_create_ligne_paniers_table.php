<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLignePaniersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_paniers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('panier_id')->index();
            $table->unsignedBigInteger('produit_id')->index();
            $table->float('quantity');
            $table->timestamps();
            $table->foreign('panier_id')->references('id')->on('paniers');
            $table->foreign('produit_id')->references('id')->on('produits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ligne_paniers');
    }
}
