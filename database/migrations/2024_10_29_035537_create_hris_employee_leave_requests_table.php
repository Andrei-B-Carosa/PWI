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
        Schema::create('hris_employee_leave_requests', function (Blueprint $table) {
            $table->id();

            $table->integer('leave_type_id');
            $table->integer('emp_id')->nullable();
            $table->date('leave_filing_date');
            $table->date('leave_date_from');
            $table->date('leave_date_to');
            $table->longText('reason');
            $table->string('is_excused',5)->nullable();

            $table->tinyInteger('is_approved')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();

            $table->tinyInteger('is_deleted')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_employee_leave_requests');
    }
};
