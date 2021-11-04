<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vcard extends Model
{
    use HasFactory;


    public $timestamps = true;


    protected $fillable = [
        'name',
        'email',
        'photo_url',
        'password',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit',
        'custom_options',
        'custom_data',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class,'vcard');
    }



    public function pair_transactions()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard');
    }

    public function git()
    {
        return $this->hasMany(Category::class, 'vcard');
    }




}

