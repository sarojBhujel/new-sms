<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty;

class StudentFiscalDetail extends Model
{
    protected $table = 'student_fiscal_details';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'active_fiscal_year_id',
        'faculty_id',
        'admission_no',
        'admission_date',
        'class_id',
        'section_id',
        'roll_no',
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'academic_year_id');
    }

    public function activeFiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'active_fiscal_year_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    // public function faculty()
    // {
    //     return $this->belongsTo(Faculty::class, 'faculty_id');
    // }
}
