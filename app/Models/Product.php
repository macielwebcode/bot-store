<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo("category");
    }

    public function hasFavorites(){
        return $this->belongsToMany(User::class, "favorites");
    }

    public function hasActive(){

    }
}
