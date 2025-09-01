<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encargado_municipios', function (Blueprint $table) {
            $table->id();
            $table->char('user_uuid', 36);
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_uuid','municipio_id']);
            $table->foreign('user_uuid')->references('uuid')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encargado_municipios');
    }
};

