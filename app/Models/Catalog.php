<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'shared',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sharedUsers(){
        return $this->belongsToMany(User::class, 'shared_catalog');
    }
}
