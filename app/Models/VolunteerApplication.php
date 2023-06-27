<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'users_id',
        'status',
        'volunteer_type',
        'application_date',
        'approval_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
