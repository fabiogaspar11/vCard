<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefaultCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'type',
        'name'
    ];
}
