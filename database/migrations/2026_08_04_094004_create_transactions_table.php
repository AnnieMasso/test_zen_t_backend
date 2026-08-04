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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnUpdate();
            $table->foreignId('id_agent')->constrained('agents')->cascadeOnUpdate();
            $table->string('type', 191);
            $table->integer('montant');
            $table->integer('numero_ex');
            $table->integer('numero_des');
            $table->integer('comission')->nullable();
            $table->string('statut', 191)->default('initié');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
