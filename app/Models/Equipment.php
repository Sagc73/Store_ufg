<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $guarded = [];
    //un eq tiene muchos historiales de prestamo
    public function loans(){
        return $this->hasMany(Loan::class);
    }
    //verificador para ver si se puede eliminar
    public function getCanBeDeletedAttribute(){
        //no borrar si esta prestado a un cliente
        return $this->status !== 'rented';
    }

}
