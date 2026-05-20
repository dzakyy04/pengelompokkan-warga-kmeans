<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('master_bansos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('skor')->comment('3=Tidak Menerima, 2=Sembako, 1=PKH/BLT (semakin tinggi = semakin sejahtera)');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('master_bansos'); }
};
