<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;
    
    protected $fillable = ['privilege']; // Add any fields you want to make mass-assignable

    /**
     * The users that belong to the privilege.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'privilege_user');
    }
}
