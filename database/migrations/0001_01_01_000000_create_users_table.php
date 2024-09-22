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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->nullable();
            $table->string('given_name');
            $table->string('family_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('user_type', ['client', 'staff', 'applicant']);
            $table->enum('status', ['active', 'unconfirmed', 'suspended', 'banned', 'unknown'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
