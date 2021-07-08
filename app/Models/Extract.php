<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extract extends Model
{
    use HasFactory;

    protected $fillable = array(
        'user_id',
        'balance',
        'old_balance',
        'operation',
        'description'
    );

    public function user(){
        return $this->belongsTo(User::class);
    }
}
