<?php

namespace App\Models;

use App\Models\SubjectName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
#use Spatie\Translatable\HasTranslations;



class Subject extends Model
{
    use HasFactory;
  #  use HasTranslations;


    protected $fillable = [ 'grade_id', 'classroom_id', 'teacher_id', 'subject_name_id'];


    // جلب اسم المراحل الدراسية

    public function grade()
    {
        return $this->belongsTo('App\Models\Grade', 'grade_id');
    }

    // جلب اسم الصفوف الدراسية
    public function classroom()
    {
        return $this->belongsTo('App\Models\Classroom', 'classroom_id');
    }

    // جلب اسم المعلم
    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher', 'teacher_id');
    }

    public function subjectName()
    {
        return $this->belongsTo(SubjectName::class, 'subject_name_id');
    }
}
