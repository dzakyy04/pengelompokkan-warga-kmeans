<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clustering_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_cluster')->default(3);
            $table->integer('max_iterasi')->default(100);
            $table->integer('iterasi_tercapai')->nullable();
            $table->enum('status', ['pending', 'completed', 'validated', 'rejected'])->default('pending');
            $table->text('catatan_validasi')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clustering_sessions'); }
};
