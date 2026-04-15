<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('file_path', 500);
            $table->string('file_type', 20)->default('image'); // image, pdf, video
            $table->string('mime_type', 100)->nullable();
            $table->integer('file_size_kb')->nullable();
            $table->string('original_name', 255)->nullable();
            $table->boolean('is_cover')->default(false);
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_media');
    }
};
