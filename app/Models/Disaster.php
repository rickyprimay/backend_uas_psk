<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    protected $fillable = [
        'title',
        'content',
        'description',
        'location',
        'image',
        'author',
    ];
}
