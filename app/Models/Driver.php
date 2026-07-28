<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'client_id',
        'driver_name',
        'plate_number',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
