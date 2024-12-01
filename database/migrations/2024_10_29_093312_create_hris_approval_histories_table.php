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
        Schema::create('hris_approval_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('entity_id')->comment('primary id on the table : overtime_table, leave_table, ob_table');
            $table->tinyInteger('entity_table')->comment('overtime_table, leave_table, ob_table');
            $table->integer('emp_id');
            $table->tinyInteger('is_approved')->nullable();
            $table->tinyInteger('approver_level')->nullable();
            $table->tinyInteger('approver_type')->nullable()->comment('1=Department Level ,2=Section Level');
            $table->string('approver_remarks')->nullable();

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
        Schema::dropIfExists('hris_approval_histories');
    }
};
