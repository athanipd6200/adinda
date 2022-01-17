<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->id('id_entri');
            $table->string('id_organisasi')->nullable();
            $table->string('id_divisi')->nullable();
            $table->string('nama_divisi')->nullable();
            $table->string('alamat_divisi')->nullable();
            $table->string('logo_divisi')->nullable();
            $table->mediumText('keterangan_divisi')->nullable();
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
        Schema::dropIfExists('divisis');
    }
}
