<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });

        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->index(['doctor_id', 'available_date', 'is_available'], 'doctor_schedule_availability_index');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['patient_id', 'status', 'appointment_date'], 'patient_appointment_status_date_index');
            $table->index(['doctor_id', 'status', 'appointment_date'], 'doctor_appointment_status_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('patient_appointment_status_date_index');
            $table->dropIndex('doctor_appointment_status_date_index');
        });

        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->dropIndex('doctor_schedule_availability_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
