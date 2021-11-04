<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_types extends Model
{
    use HasFactory;

    public $timestamps = true;


    protected $fillable = [
        'name',
        'description',
        'validation_rules',
        'custom_options',
        'custom_data',
    ];

}
