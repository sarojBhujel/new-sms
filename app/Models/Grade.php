<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
#use Spatie\Translatable\HasTranslations;



class Grade extends Model
{

  #  use HasTranslations;
    public $translatable  = ['Name'];

    protected $fillable=['Name','Notes','has_faculty'];
    protected $table = 'grades';
    public $timestamps = true;

    public function Section()
    {
        return $this->hasMany('App\Models\Section','Grade_id');
    }

    public function faculties()
    {
      return $this->hasMany('App\Models\Faculty', 'grade_id');
    }

}