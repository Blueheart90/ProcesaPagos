<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    // Por defecto Laravel toma como PK el campo id y realiza un autoincremento
    // Para cambiar este comportamiento definimos las variable sgtes
    protected $primaryKey = 'iso';
    public $incrementing = false;

    protected $fillable = [
        'iso',
    ];
}
