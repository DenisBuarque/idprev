<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','email','zip_code','address','number','district','city','state','tag','process','situation','financial','action','court','stick','term','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbackLeads()
    {
        return $this->hasMany(FeedbackLead::class);
    }

    public function photos()
    {
        return $this->hasMany(ClientPhotos::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'term' => 'datetime'
    ];
}
