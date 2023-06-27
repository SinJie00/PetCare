<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'age',
        'gender',
        'type',
        'status',
        'image',
    ];
}
