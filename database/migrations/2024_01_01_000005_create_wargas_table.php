<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('rt_rw')->nullable();
            $table->foreignId('pekerjaan_id')->constrained('master_pekerjaan')->onDelete('restrict');
            $table->decimal('pendapatan', 15, 0)->default(0);
            $table->integer('jumlah_tanggungan')->default(0);
            $table->foreignId('kondisi_rumah_id')->constrained('master_kondisi_rumah')->onDelete('restrict');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('wargas'); }
};
