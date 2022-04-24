<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPhotos extends Model
{
    use HasFactory;
    
    protected $fillable = ['image'];

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
