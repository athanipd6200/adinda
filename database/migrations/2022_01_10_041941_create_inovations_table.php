<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInovationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inovations', function (Blueprint $table) {
            $table->id('id_entri');
            $table->string('id_inovation');
            $table->string('nama_inovation');
            $table->string('ikon_inovation')->nullable();
            $table->mediumText('gambar_inovation')->nullable();
            $table->string('satker_asal_inovation')->nullable();
            $table->string('kontak_hubung_inovation')->nullable();
            $table->mediumText('deskripsi_inovation')->nullable();
            $table->longText('konten_inovation')->nullable();
            $table->string('tautan_materi_inovation')->nullable();
            $table->string('tautan_kode_inovation')->nullable();
            $table->string('created_by')->nullable(); //email
            $table->string('updated_by')->nullable(); //email
            $table->string('verified_by')->nullable(); //email
            $table->string('supervised_by')->nullable(); //email
            $table->string('created_by_group')->nullable(); //id_grup
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
        Schema::dropIfExists('inovations');
    }
}
