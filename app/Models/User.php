<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    //Todos los datos podrán ser asignados en una sola acción
    protected $guarded = [];

    //No mostraremos
    protected $hidden = ['created_at', 'updated_at'];

    //PERTENECE A: Método para devolver el grupo al que pertenece un usuario
    public function group(){
        return $this->belongsTo(Group::class, 'groupId');
    }

    //TIENE UN: Méodo para devolver el carnet que posee un usuario
    public function card(){
        return $this->hasOne(Card::class, 'id');
    }

    //PERTENECE A: Metodo para devolver el rol que posee un usuario
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol');
    }

    //TIENE UNAS: Metodo para devolver todas las entradas del usuario
    public function entry(){
        return $this->hasMany(userEntry::class, 'userId'); 
    }
    
    //TIENE UNAS: Metodo para devolver todas las salidas del usuario
    public function exit(){
        return $this->hasMany(userExit::class, 'userId'); 
    }
}
