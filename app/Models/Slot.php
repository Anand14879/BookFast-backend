<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = ['is_available', 'date', 'facility_id'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
