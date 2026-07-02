<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $connection = 'mysql';

    protected $fillable = [
    'transaction_no',
    'plate_number',
    'driver_name',
    'representative_name',
    'transaction_type',
    'material',
    'product',
    'company',
    'address',
    //'moisture_content',
    'gross_weight',
    'tare_weight',
    'net_weight',
    'clerk',
    'approved_by',
    'gross_time',
    'tare_time',
    'net_time',
    'client_id',
];

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
