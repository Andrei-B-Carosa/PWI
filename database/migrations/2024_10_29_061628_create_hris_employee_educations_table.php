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
        Schema::create('hris_employee_educations', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('school')->nullable();
            $table->integer('level')->nullable();
            $table->string('degree')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->year('year_graduate')->nullable();
            $table->string('honors')->nullable();
            $table->tinyInteger('is_current')->nullable();

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
        Schema::dropIfExists('hris_employee_educations');
    }
};
