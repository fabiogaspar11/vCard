<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'vcard',
        'type',
        'name'
    ];


    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }


}
