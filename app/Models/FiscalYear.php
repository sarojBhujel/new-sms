<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date_np',
        'end_date_np',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function studentFiscalDetails()
    {
        return $this->hasMany(StudentFiscalDetail::class, 'academic_year_id');
    }

    public static function active(): ?self
    {
        return static::where('status', true)->first();
    }

    public static function requireActive(): self
    {
        $fiscalYear = static::active();

        if (!$fiscalYear) {
            throw new \Exception('Please create and activate a fiscal year before performing this operation.');
        }

        return $fiscalYear;
    }
}
