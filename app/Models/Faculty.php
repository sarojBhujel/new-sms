<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_name',
        'faculty_code',
        'grade_id',
        'status',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function studentFiscalDetails()
    {
        return $this->hasMany(StudentFiscalDetail::class, 'faculty_id');
    }
}
