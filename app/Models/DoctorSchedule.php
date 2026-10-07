<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'available_date',
        'start_time',
        'end_time',
        'appointment_duration',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'available_date' => 'date',
            'is_available' => 'boolean',
            'appointment_duration' => 'integer',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
