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
        Schema::create('translation_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_id')->constrained()->cascadeOnDelete();
            $table->string('language', 10)->index();
            $table->text('value')->fulltext()->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['translation_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_values');
    }
};
