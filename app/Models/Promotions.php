<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }

    // Relation between promotions and grades to retrieve the grade name in the promotions table

    public function f_grade()
    {
        return $this->belongsTo('App\Models\Grade', 'from_grade');
    }


    // Relation between promotions and classrooms to retrieve the classroom name in the promotions table

    public function f_classroom()
    {
        return $this->belongsTo('App\Models\Classroom', 'from_Classroom');
    }

    // Relation between promotions and sections to retrieve the section name in the promotions table

    public function f_section()
    {
        return $this->belongsTo('App\Models\Section', 'from_section');
    }

    // Relation between promotions and target grades to retrieve the grade name in the promotions table

    public function t_grade()
    {
        return $this->belongsTo('App\Models\Grade', 'to_grade');
    }


    // Relation between promotions and target classrooms to retrieve the classroom name in the promotions table

    public function t_classroom()
    {
        return $this->belongsTo('App\Models\Classroom', 'to_Classroom');
    }

    // Relation between promotions and target sections to retrieve the section name in the promotions table

    public function t_section()
    {
        return $this->belongsTo('App\Models\Section', 'to_section');
    }

}
