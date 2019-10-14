<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'image'
    ];

    protected $dates = ['deleted_at'];

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
