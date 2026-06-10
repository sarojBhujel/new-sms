<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization_name',
        'specialization_code',
        'description',
        'status',
        'Name',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
