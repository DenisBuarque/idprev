<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title','date_event','description','slug','image'];

    protected $casts = [
        'date_event' => 'datetime',
    ];
}
