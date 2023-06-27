<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionApplication extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'adoption_animals_id', 'status', 'application_date', 'approval_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function adoptionAnimal()
    {
        return $this->belongsTo(AdoptionAnimal::class, 'adoption_animals_id');
    }
}
