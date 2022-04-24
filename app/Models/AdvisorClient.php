<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorClient extends Model
{
    use HasFactory;

    protected $table = 'advisor_client';

    protected $fillable = ['advisor_id','client_id'];
}
