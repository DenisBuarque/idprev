<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = ['folder','client_id','title',
    'tag','instance','number_process',
    'juizo','vara','foro','action','days',
    'description','valor_causa','data',
    'valor_condenacao','detail'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
