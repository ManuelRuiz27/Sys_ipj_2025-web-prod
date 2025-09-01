<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->string('seccional')->unique();
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('distrito_local');
            $table->string('distrito_federal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};

