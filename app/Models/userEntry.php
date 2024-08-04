<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'cardId',
        'entryDate'
    ];

    //PERTENECE A: Metodo para devolver el usuario relacionado a la entrada
    public function user(){

        return $this->belongsTo(User::class, 'userId'); 
    }
}
