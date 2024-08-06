<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'urlQr',
        'urlImage',
        'userId',
        'issueDate',
        'expiryDate',
        'status'
    ]; 

    //No mostraremos
    protected $hidden = ['created_at', 'updated_at'];

    //Metodo para devolver el usuario asociado al carnet
    public function User(){
        return $this->belongsTo(User::class); 
    }

}
