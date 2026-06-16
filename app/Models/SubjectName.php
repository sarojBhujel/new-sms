<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'status'];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
