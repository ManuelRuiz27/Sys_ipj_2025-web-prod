<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('clave')->unique();
            $table->string('nombre');
            $table->timestamps();

            $table->index('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};

