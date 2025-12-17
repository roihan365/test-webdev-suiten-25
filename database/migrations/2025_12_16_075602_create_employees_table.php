<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique()->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('telepon');
            $table->text('alamat');
            $table->foreignId('bagian_id')->constrained('sections')->onDelete('restrict');

            $table->string('no_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('bank')->nullable();
            $table->enum('shift', ['pagi', 'siang', 'malam', 'full_day'])->default('full_day');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->enum('periode_gaji', ['bulanan', 'mingguan', 'harian'])->default('bulanan');
            $table->decimal('gaji_harian', 15, 2)->default(0);
            $table->decimal('uang_makan', 15, 2)->default(0);
            $table->decimal('uang_makan_tanggal_merah', 15, 2)->default(0);
            $table->decimal('rate_lembur', 15, 2)->default(0);
            $table->decimal('rate_lembur_tanggal_merah', 15, 2)->default(0);

            $table->string('jabatan');
            $table->date('tgl_masuk');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto')->nullable();

            // Indexes
            $table->index('bagian_id');
            $table->index('status');
            $table->index('shift');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
