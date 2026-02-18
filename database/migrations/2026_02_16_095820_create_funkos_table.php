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
        Schema::create('funkos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()-onDelete('cascade'); //relacion con tabla de categories
            $stable->string('era')->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable(), //para la parte 2 de proyecto es opcional ahroa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funkos');
    }
};
