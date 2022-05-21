<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','lead_id','precatory','receipt_date','bank','value_total','value_client','fees','fees_received','payday','payment_amount','payment_bank','confirmation_date','people','contact','payment_confirmation'];

    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    protected $casts = [
        'receipt_date' => 'datetime'
    ];
}