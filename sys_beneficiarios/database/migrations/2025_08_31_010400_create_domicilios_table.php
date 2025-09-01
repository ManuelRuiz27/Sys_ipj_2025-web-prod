<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domicilios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('beneficiario_id', 36);
            $table->string('calle');
            $table->string('numero_ext');
            $table->string('numero_int')->nullable();
            $table->string('colonia');
            $table->string('municipio');
            $table->string('codigo_postal');
            $table->string('seccional');
            $table->timestamps();

            $table->foreign('beneficiario_id')->references('id')->on('beneficiarios')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};

