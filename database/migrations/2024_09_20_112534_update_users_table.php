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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('nickname')->nullable()->after('id');
            $table->string('given_name')->after('nickname');
            $table->string('family_name')->after('given_name');
            $table->foreignId('company_id')->nullable()->after('email')->constrained()->onDelete('set null');
            $table->enum('user_type', ['client', 'staff', 'applicant', 'administrator', 'super-user']);
            $table->enum('status', ['active', 'unconfirmed', 'suspended', 'banned', 'unknown', 'suspended'])->default('active')->after('user_type');
            $table->softDeletes()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn(['nickname', 'given_name', 'family_name', 'company_id', 'user_type', 'status']);
            $table->dropSoftDeletes();
        });
    }
};
