<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract',
        'date',
        'value',
        'files',
        'status',
        'success_log',
        'periot_num',
    ];

    public function getFilesAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getSuccess_logAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getCont() {
        return $this->belongsTo(Contract::class, 'contract');
    }
}
