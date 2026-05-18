<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cluster_centroids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('clustering_sessions')->onDelete('cascade');
            $table->integer('cluster');
            $table->string('label');
            $table->decimal('centroid_pendapatan', 15, 6)->nullable();
            $table->decimal('centroid_pekerjaan', 15, 6)->nullable();
            $table->decimal('centroid_tanggungan', 15, 6)->nullable();
            $table->decimal('centroid_kondisi_rumah', 15, 6)->nullable();
            $table->decimal('centroid_aset', 15, 6)->nullable();
            $table->integer('jumlah_anggota')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cluster_centroids'); }
};
