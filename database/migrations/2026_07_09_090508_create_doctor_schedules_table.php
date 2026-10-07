<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('available_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('appointment_duration')->default(30);
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->unique(
                [
                    'doctor_id',
                    'available_date',
                    'start_time',
                    'end_time',
                ],
                'doctor_schedule_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
