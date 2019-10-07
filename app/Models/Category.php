<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
     * Get products of categories
     *
     * @return
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }
}
