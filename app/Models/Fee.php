<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\FeeNames;

class Fee extends Model
{
    public $translatable = ['title'];

    protected $fillable = [
        'fee_name_id',
        'title',
        'amount',
        'months',
        'Grade_id',
        'Classroom_id',
        'description',
        'Fee_type',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'Grade_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'Classroom_id');
    }

    public function feeName()
    {
        return $this->belongsTo(FeeNames::class, 'fee_name_id');
    }
}
