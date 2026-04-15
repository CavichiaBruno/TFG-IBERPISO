<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->decimal('surface_m2', 8, 2);
            $table->tinyInteger('rooms')->default(0);
            $table->tinyInteger('bathrooms')->default(0);
            $table->tinyInteger('floor')->nullable();
            $table->string('property_type', 30)->default('piso'); // piso, casa, chalet, local, garaje, oficina
            $table->string('operation_type', 20)->default('venta'); // venta, alquiler
            $table->string('address', 255);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('postal_code', 10);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_terrace')->default(false);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_pool')->default(false);
            $table->boolean('air_conditioning')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('energy_certificate', 5)->nullable(); // A,B,C,D,E,F,G
            $table->string('virtual_tour_url')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
