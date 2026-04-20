<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignId('docteur_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignId('diagnostique_id')->constrained('diagnostiques')->onDelete('cascade');
            $table->dateTime('date_heure');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};
