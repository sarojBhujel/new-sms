<?php

namespace App\Models;

use App\Models\FiscalYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'student_id',
        'Debit',
        'description',
        'active_fiscal_year_id',
    ];
    
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'active_fiscal_year_id');
    }
}
