<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('master_aset', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('estimasi_nilai', 15, 0)->comment('Estimasi nilai pasar Rupiah');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('master_aset'); }
};
