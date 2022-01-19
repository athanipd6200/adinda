<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tims', function (Blueprint $table) {
            $table->id('id_entri');
            $table->string('id_organisasi')->nullable();
            $table->string('id_divisi')->nullable();
            $table->string('id_tim')->nullable();
            $table->string('nama_tim')->nullable();
            $table->string('logo_tim')->nullable();
            $table->string('alamat_tim')->nullable();
            $table->mediumText('keterangan_tim')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('tims');
    }
}
