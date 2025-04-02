<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $fillable = ['name', 'created_by', 'manager_id', 'stage_id', 'description'];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'opportunity_tag', 'opportunity_id', 'tag_id');
    }
    
}
