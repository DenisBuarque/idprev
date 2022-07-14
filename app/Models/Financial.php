<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;//'financial_id',
    protected $fillable = ['user_id','lead_id','confirmation','precatory','receipt_date','bank','value_total','value_client','fees','fees_received','installments','payday','payment_amount','payment_bank','confirmation_date','people','contact','comments'];

    public function lead (){
        return $this->belongsTo(Lead::class);
    }

    public function photos ()
    {
        return $this->hasMany(FinancialPhotos::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'receipt_date' => 'datetime'
    ];
}