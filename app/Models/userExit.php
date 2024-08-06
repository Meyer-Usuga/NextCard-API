<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userExit extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'cardId',
        'exitDate'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    //PERTENECE A: Metodo para devolver el usuario relacionado a la entrada
    public function user(){

        return $this->belongsTo(User::class, 'userId'); 
    }
}
