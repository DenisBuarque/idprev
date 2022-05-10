<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['code','status','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function feedbackTickets(){
        return $this->hasMany(FeedbackTicket::class);
    }

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
