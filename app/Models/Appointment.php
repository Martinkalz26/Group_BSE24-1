<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'consultation_id', 'start_time', 'end_time', 'total_fee', 'payment_status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultations::class);
    }
}
