<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['pipeline_id', 'name', 'position'];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }
}
