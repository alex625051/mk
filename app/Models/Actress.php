<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actress extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function films() {
        return $this->belongsToMany(Film::class);
    }
}
