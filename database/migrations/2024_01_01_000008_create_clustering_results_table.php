<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('clustering_sessions')->onDelete('cascade');
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->integer('cluster');
            $table->string('label');
            $table->decimal('jarak_ke_centroid', 10, 6)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clustering_results'); }
};
