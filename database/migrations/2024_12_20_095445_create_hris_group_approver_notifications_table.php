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
        Schema::create('hris_group_approver_notifications', function (Blueprint $table) {
            $table->id();

            $table->integer('entity_id')->comment('primary id on the table : overtime_table, leave_table, ob_table');
            $table->tinyInteger('entity_table')->comment('1=overtime_table,2=leave_table,3=ob_table');
            $table->integer('group_id')->comment('Group ID for approver notifications');

            $table->text('request_link_token')->nullable();  // To store the encrypted link
            $table->boolean('is_approved')->nullable()->nullable();  // To track approval status (approved or rejected)

            $table->integer('emp_id');
            $table->tinyInteger('approver_level')->nullable();
            $table->tinyInteger('is_final_approver')->nullable()->comment('1=final approver ,null=not final approver');

            $table->dateTime('link_expired_at')->nullable();
            $table->tinyInteger('link_status')->nullable();


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
        Schema::dropIfExists('hris_group_approver_notifications');
    }
};
