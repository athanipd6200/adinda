<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtikelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id('id_entri');
            $table->string('id_artikel');
            $table->string('judul_artikel');
            $table->string('jenis_artikel')->nullable();
            $table->string('gambar_pembuka_artikel')->nullable();
            $table->mediumtext('teks_pembuka_artikel');
            $table->longtext('teks_isi_artikel')->nullable();
            $table->string('tags_artikel')->nullable();
            $table->date('tanggal_artikel')->nullable();
            $table->mediumtext('geolocation_artikel')->nullable();
            $table->string('tampilan_web')->nullable();
            $table->string('verification_artikel')->nullable();
            $table->string('verificator_artikel')->nullable();
            $table->string('email_creator_artikel')->nullable();
            $table->string('organization_creator_artikel')->nullable();
            $table->string('sub_organization_creator_artikel')->nullable();
            $table->string('penulis_artikel')->nullable();
            $table->string('penyunting_artikel')->nullable();
            $table->mediumtext('lampiran_artikel')->nullable();
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
        Schema::dropIfExists('artikels');
    }
}
