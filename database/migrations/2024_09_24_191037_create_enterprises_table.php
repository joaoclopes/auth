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
        Schema::create('enterprises', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->boolean('can_activate_user')->default(false);
            $table->json('personalized_messages')->nullable();
            $table->boolean('multi_enterprise')->default(false);
            $table->uuid('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('uuid')->on('enterprises')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprises');
    }
};
