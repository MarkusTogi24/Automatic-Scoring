<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_dan_soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->integer('id_soal_ujian');
            $table->integer('id_siswa');
            $table->longtext('jawaban');
            $table->float('score');
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
        Schema::dropIfExists('siswa_dan_soal_ujian');
    }
};
