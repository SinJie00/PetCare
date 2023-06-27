<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrayPost extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'location', 'latitude','longitude','users_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
