<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle');
            $table->string('origin');
            $table->float('unit_price');
            $table->string('unit');
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('import_id')->index();
            $table->timestamps();
            
            
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('import_id')->references('id')->on('imports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produits');
    }
}
