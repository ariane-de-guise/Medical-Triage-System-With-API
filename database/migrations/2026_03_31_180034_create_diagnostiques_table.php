<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('comptes')->onDelete('cascade'); // lien vers Compte
            $table->string('commentaire');
            $table->text('description');
            $table->dateTime('date_heure');
            $table->integer('priorite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostiques');
    }
};
