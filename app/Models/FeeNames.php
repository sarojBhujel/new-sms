<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fee;

class FeeNames extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'amount',
        'type',
        'months',
        'description',
        'active',
    ];

    public function fees()
    {
        return $this->hasMany(Fee::class, 'fee_name_id');
    }
}
