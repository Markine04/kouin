<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('pseudo')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('password')->nullable();
            $table->string('confirm_password')->nullable();
            $table->string('localisation_entreprise')->nullable();
            $table->string('nom_prenoms')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('fonction')->nullable();
            $table->string('contact')->nullable();
            $table->string('nom_entreprise')->nullable();
            $table->string('registre_commerce')->nullable();
            $table->string('compte_contribuable')->nullable();
            $table->string('piece_identite')->nullable();
            $table->string('logo_entreprise')->nullable();
            $table->string('rccm')->nullable();
            $table->integer('is_active')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
