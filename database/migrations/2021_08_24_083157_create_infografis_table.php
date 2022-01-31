<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfografisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infografis', function (Blueprint $table) {
            $table->id('id_entri');
            $table->string('name')->nullable();
            $table->string('source')->nullable();
            $table->longtext('description')->nullable();
            $table->string('inserted_by')->nullable();
            $table->string('index_infografis')->nullable();
            $table->string('tags_infografis')->nullable();
            $table->string('tautan_infografis')->nullable();
            $table->string('tampilan_web')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infografis');
    }
}
