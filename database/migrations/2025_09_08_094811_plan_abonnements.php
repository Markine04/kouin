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
        Schema::create('plan_abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('prix')->default(10.2);
            $table->integer('periode_id');
            $table->string('description');
            $table->integer('is_active');
            $table->integer('user_enreg');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_abonnements');
    }
};
