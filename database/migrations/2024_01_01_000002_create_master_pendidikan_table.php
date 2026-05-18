<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('master_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('skor')->comment('Tingkat pendidikan: 1=Tidak Sekolah, semakin tinggi semakin tinggi pendidikannya');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('master_pendidikan'); }
};
