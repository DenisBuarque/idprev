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

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function photos()
    {
        return $this->hasMany(ClientPhotos::class);
    }

    public function advisor()
    {
        return $this->hasMany(Advisor::class);
    }
}