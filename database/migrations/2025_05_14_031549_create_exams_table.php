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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama ujian
            $table->integer('duration')->default(30); // Durasi dalam menit
            $table->boolean('shuffle_questions')->default(true); // Acak soal
            $table->boolean('shuffle_options')->default(true); // Acak opsi
            $table->integer('institution_id'); // ID institusi, nullable jika tidak ada
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
