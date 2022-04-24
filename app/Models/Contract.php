<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['process_id','description'];

    public function advisors()
    {
        return $this->belongsToMany(Advisor::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
