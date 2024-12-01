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
        Schema::create('hris_employee_official_business_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->date('ob_filing_date');
            $table->time('ob_time_out');
            $table->time('ob_time_in');
            $table->longText('destination');
            $table->integer('contact_person_id')->nullable();
            $table->longText('purpose');

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
        Schema::dropIfExists('hris_employee_official_business_requests');
    }
};
