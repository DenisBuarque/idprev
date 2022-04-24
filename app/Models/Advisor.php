<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Advisor extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['name','phone','email','password','zip_code','address','number','district','city','state','complement'];

    public function constract()
    {
        return $this->belongsToMany(Contract::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


}
