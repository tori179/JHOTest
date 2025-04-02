<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    protected $fillable = ['name'];

    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('position');
    }
}
