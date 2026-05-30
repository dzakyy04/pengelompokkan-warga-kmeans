<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update wargas table: hapus pendapatan, tambah pekerjaan & status_produktivitas
        Schema::table('wargas', function (Blueprint $table) {
            $table->string('pekerjaan')->nullable()->after('rt_rw');
            $table->string('status_produktivitas')->nullable()->after('pekerjaan');
            $table->integer('skor_produktivitas')->default(1)->after('status_produktivitas');
        });

        // Hapus kolom pendapatan setelah kolom baru ditambahkan
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn('pendapatan');
        });

        // 2. Rename centroid_pendapatan → centroid_pekerjaan di cluster_centroids
        Schema::table('cluster_centroids', function (Blueprint $table) {
            $table->renameColumn('centroid_pendapatan', 'centroid_pekerjaan');
        });
    }

    public function down(): void
    {
        // Reverse: centroid_pekerjaan → centroid_pendapatan
        Schema::table('cluster_centroids', function (Blueprint $table) {
            $table->renameColumn('centroid_pekerjaan', 'centroid_pendapatan');
        });

        // Reverse wargas
        Schema::table('wargas', function (Blueprint $table) {
            $table->decimal('pendapatan', 15, 0)->default(0)->after('rt_rw');
        });

        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn(['pekerjaan', 'status_produktivitas', 'skor_produktivitas']);
        });
    }
};
