<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telephones_comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignId('telephone_id')->constrained('telephones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telephones_comptes');
    }
};
