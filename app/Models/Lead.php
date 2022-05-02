<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','email','cep','address','number','district','city','state','tag','process','situation','financial','action','court','stick','term','advisor_id'];

    public function advisors()
    {
        return $this->hasMany(Advisor::class);
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
    'created_at' => 'datetime'
];
}
