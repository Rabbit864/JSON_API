<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','description','date_creation','price','quantity','external_id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
