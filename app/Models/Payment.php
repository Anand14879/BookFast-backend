<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = ['amount', 'payment_date', 'payment_status', 'booking_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
