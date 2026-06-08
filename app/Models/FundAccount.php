<?php

namespace App\Models;

use App\Models\FiscalYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'receipt_id',
        'payment_id',
        'Debit',
        'credit',
        'description',
        'active_fiscal_year_id',
    ];

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class, 'active_fiscal_year_id');
    }
}
