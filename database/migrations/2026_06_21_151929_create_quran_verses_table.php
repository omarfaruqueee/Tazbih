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
        Schema::create('quran_verses', function (Blueprint $table) {
            $table->id();
            $table->text('text_ar');
            $table->text('text_bn');
            $table->text('text_en');
            $table->string('surah_name');
            $table->integer('verse_no');
            $table->date('show_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_verses');
    }
};
