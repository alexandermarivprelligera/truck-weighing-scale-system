<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $connection = 'mysql';

    protected $fillable = [
        'company',
        'address',

        'branch_code',
        'tin_number',
        'contact_person',
        'contact_number',
        'mayor'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 
        'company', 'company');
    }
}