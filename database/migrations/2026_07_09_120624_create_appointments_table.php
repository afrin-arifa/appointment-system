<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('doctor_schedule_id')
                ->constrained('doctor_schedules')
                ->cascadeOnDelete();

            $table->date('appointment_date');
            $table->time('appointment_time');

            $table->enum('status', [
                'pending',
                'approved',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->text('symptoms')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'doctor_id',
                    'appointment_date',
                    'appointment_time',
                ],
                'doctor_appointment_slot_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
