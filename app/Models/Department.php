<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Department extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'initials',
        'name',
    ];
    public $timestamps = false;

    //sjam shume i qarte a duhet kjo
    public function rules()
    {
        return [
            'initials' => [
                'required',
                Rule::unique('departments', 'initials')
            ]
        ]; 
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
