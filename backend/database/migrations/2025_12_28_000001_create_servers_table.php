<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('pterodactyl_server_id')->unique();
            $table->string('name');
            $table->string('identifier')->nullable(); // Pterodactyl identifier (short UUID)
            $table->unsignedBigInteger('node_id');
            $table->unsignedBigInteger('egg_id');
            $table->string('status')->default('installing'); // installing, running, suspended, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
