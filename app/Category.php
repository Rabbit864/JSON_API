<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','external_id','parent_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
