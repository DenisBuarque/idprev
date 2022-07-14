<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = ['term','audiencia','hour','tag','address','comments','lead_id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
