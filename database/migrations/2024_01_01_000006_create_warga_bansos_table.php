<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('warga_bansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->foreignId('master_bansos_id')->constrained('master_bansos')->onDelete('cascade');
            $table->unique(['warga_id', 'master_bansos_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('warga_bansos'); }
};
