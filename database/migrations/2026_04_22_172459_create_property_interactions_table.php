<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_interactions', function (Blueprint $blade) {
            $blade->id();
            $blade->foreignId('user_id')->constrained()->onDelete('cascade');
            $blade->foreignId('property_id')->constrained()->onDelete('cascade');
            $blade->enum('type', ['like', 'dislike']);
            $blade->timestamps();

            // Evitar duplicados: un usuario solo interactúa una vez por propiedad
            $blade->unique(['user_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_interactions');
    }
};
