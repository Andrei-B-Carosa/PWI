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
        Schema::create('hris_leave_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_type_id');

            // New 'credit_type' column:
            // 1 = automatic credits 2 = manual credit
            $table->tinyInteger('credit_type')->nullable();

            $table->json('classification_id')->nullable();
            $table->json('employment_id')->nullable();
            $table->json('location_id')->nullable();

            $table->float('start_credit')->nullable();
            $table->tinyInteger('is_carry_over')->nullable();
            $table->integer('carry_over_month')->nullable();
            $table->integer('carry_over_day')->nullable();

            // 1 = Monthly Credit Distribution 2 = Annual Credit Distribution 3 =Pro-rata Credit Distribution
            $table->tinyInteger('fiscal_year')->nullable();

            $table->string('is_reset')->nullable();
            $table->integer('reset_month')->nullable();
            $table->integer('reset_day')->nullable();

            // 1 =fixed, 2 =increment
            $table->tinyInteger('assign_type')->nullable();

            $table->tinyInteger('succeeding_year')->nullable();
            $table->json('increment_milestones')->nullable();

            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('hris_leave_settings');
    }
};
