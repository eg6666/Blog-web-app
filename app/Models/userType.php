<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userType extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];  
    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
