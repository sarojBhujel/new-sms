<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\FeeNames;
use App\Models\FiscalYear;

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
        'active_fiscal_year_id',
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

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'active_fiscal_year_id');
    }
}
