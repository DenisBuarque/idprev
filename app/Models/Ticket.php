<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['code','status','advisor_id'];

    public function advisor(){
        return $this->belongsTo(Advisor::class);
    }

    public function feedbackTickets(){
        return $this->hasMany(FeedbackTicket::class);
    }

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
