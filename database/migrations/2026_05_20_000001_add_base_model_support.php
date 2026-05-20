<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clustering_sessions', function (Blueprint $table) {
            $table->boolean('is_base_model')->default(false);
            $table->json('normalization_params')->nullable();
        });

        Schema::create('warga_classification_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->foreignId('base_model_session_id')->constrained('clustering_sessions')->onDelete('cascade');
            $table->integer('assigned_cluster');
            $table->string('assigned_label');
            $table->decimal('distance_to_centroid', 10, 6);
            $table->json('distances_to_all_centroids')->nullable();
            $table->json('feature_values')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('revised_cluster')->nullable();
            $table->string('revised_label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warga_classification_queue');

        Schema::table('clustering_sessions', function (Blueprint $table) {
            $table->dropColumn(['is_base_model', 'normalization_params']);
        });
    }
};
