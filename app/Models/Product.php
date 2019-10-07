<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get category of product
     *
     * @return
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'id');
    }
}
