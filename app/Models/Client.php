<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'email',
        'zip_code',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function photos()
    {
        return $this->hasMany(ClientPhotos::class);
    }

    protected $casts = [
        'term' => 'datetime'
    ];
}
