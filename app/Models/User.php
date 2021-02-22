<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticable implements JWTSubject
{
    use Notifiable;

    protected $primaryKey = 'id';

    protected $fillable = [
        'fname',
        'lname',
        'gender',
        'birthday',
        'email',
        'password',
        'user_type_id',
        'department_id',
        'access'
    ];
    protected $guarded = [
        'fname',
        'lname',
        'gender',
        'birthday',
        'email',
    ];

    protected $hidden = [
        'password'
    ];

    public function userType()
    {
        return $this->belongsTo(userType::class, 'user_type_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [
            'fname' => $this->fname,
            'lname' => $this->lname,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'user_type_id' => $this->user_type_id,
            'department_id' => $this->department_id,
            'access' => $this->access
        ];
    }
}
