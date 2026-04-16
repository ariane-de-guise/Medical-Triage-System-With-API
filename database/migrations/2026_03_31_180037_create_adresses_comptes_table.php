<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adresses_comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adresse_id')->constrained('adresses')->onDelete('cascade');
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->string('type'); // home/work
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adresses_comptes');
    }
};
