<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'city', 'state', 'country']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
