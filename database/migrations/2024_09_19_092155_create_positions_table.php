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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->date('advertising_start_date');
            $table->date('advertising_end_date');
            $table->string('title');
            $table->text('description');
            $table->string('keywords')->nullable();
            $table->decimal('min_salary', 10, 2);
            $table->decimal('max_salary', 10, 2);
            $table->string('salary_currency')->default('AUD');
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id');
            $table->text('benefits')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('position_type', ['permanent', 'contract', 'part-time', 'casual']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
