<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeanggotaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keanggotaans', function (Blueprint $table) {
            $table->id('id_entri');
            $table->integer('id_user');
            $table->uuid('id_keanggotaan')->nullable();
            $table->string('jenis_keanggotaan')->nullable();
            $table->string('role_keanggotaan')->default('Member');
            $table->string('permissions_keanggotaan')->nullable();
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
        Schema::dropIfExists('keanggotaans');
    }
}
