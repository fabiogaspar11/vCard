<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Default_categories extends Model
{
    use HasFactory;

    public $timestamps = true;


    protected $fillable = [
        'type',
        'name',
    ];

}
