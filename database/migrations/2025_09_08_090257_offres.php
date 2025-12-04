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
        Schema::create(
            'offres',
            function (Blueprint $table) {
                $table->id();
                $table->string('libelle');
                $table->string('code_offre')->unique();
                $table->integer('type_offre_id');
                $table->integer('formation_id');
                $table->integer('level_student_id');
                $table->string('annee_experience');
                $table->string('lieu_poste');
                $table->string('lieu_precis_poste');
                $table->date('date_publication');
                $table->dateTime('date_expiration');
                $table->text('detail_offre');
                $table->text('profil_poste');
                $table->integer('is_active');
                $table->integer('user_id')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
