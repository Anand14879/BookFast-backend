<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use CrudTrait;
    use HasFactory;

   
    protected $fillable =[
        'Name',
        'Location',
        'Description',
        'Capacity',
        'Facility_Image',
        'Daily_Cost',
        'Category',
    ];

    //These functions below help Facility table have its relationships with other tables like booking and slots
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
