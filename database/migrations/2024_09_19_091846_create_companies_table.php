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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('city_id');
            $table->foreignId('state_id');
            $table->foreignId('country_id');
            $table->string('logo')->nullable();
            $table->foreignId('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'city_id', 'state_id', 'country_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
