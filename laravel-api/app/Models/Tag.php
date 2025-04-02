<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function opportunities()
    {
        return $this->belongsToMany(Opportunity::class, 'opportunity_tag', 'tag_id', 'opportunity_id');
    }
    
}
