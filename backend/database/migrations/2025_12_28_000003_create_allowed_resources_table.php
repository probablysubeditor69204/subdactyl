<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allowed_nodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pterodactyl_node_id')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('allowed_eggs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pterodactyl_egg_id')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allowed_nodes');
        Schema::dropIfExists('allowed_eggs');
    }
};
