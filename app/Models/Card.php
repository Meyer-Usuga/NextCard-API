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

    //Metodo para devolver el usuario asociado al carnet
    public function User(){
        return $this->belongsTo(User::class); 
    }

}
