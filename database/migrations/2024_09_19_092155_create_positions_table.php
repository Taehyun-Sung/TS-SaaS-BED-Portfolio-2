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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->date('advertising_start_date');
            $table->date('advertising_end_date');
            $table->string('title');
            $table->text('description');
            $table->text('keywords')->nullable();
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            $table->string('currency')->default('AUD');
            $table->text('benefits')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('position_type', ['permanent', 'contract', 'part-time', 'casual', 'internship']);
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'title']); // Ensure each position title is unique per company
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
};
