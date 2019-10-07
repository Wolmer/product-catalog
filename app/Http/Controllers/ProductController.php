<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;

use App\Models\Category;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return new ProductCollection(Product::all());
    }
}
