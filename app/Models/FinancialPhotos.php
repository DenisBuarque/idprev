<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPhotos extends Model
{
    use HasFactory;

    protected $fillable = ['image'];

    public function financials()
    {
        return $this->belongsTo(Financial::class);
    }
}
