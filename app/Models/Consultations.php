<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultations extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'base_fee', 'extra_fee_per_minute'];

    public function bookings()
    {
        return $this->hasMany(Appointment::class);
    }
}
