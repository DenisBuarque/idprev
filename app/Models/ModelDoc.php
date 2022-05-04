<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelDoc extends Model
{
    use HasFactory;

    protected $table = 'models';

    protected $fillable = ['title','document','slug','action_id'];

    public function action(){
        return $this->belongsTo(Action::class);
    }
}
