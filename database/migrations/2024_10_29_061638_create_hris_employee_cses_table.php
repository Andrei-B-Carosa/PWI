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
        Schema::create('hris_employee_cses', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('qualification')->nullable();
            $table->string('rating')->nullable();
            $table->date('important_date')->nullable();
            $table->string('location')->nullable();
            $table->string('license_no')->nullable();
            $table->date('date_validity')->nullable();

            $table->tinyInteger('is_deleted')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_employee_cses');
    }
};
