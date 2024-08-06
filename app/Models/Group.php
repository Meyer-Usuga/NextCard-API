<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'period',
        'students',
        'teacherId',
        'status'
    ]; 

    protected $hidden = ['created_at', 'updated_at'];

    //Método para devolver todos los usuarios asociados al grupo

    public function user(){
        return $this->hasMany(User::class, 'groupId');
    }
}
