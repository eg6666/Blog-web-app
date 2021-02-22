<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $primaryKey = ['user_id', 'post_id'];
    public $incrementing = false;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    protected $guarded = [
        'user_id',
        'post_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
