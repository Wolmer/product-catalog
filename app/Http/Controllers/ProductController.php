<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;

use App\Models\Category;
use App\Http\Resources\CategoryCollection;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    public function getCategories()
    {
        return new CategoryCollection(Category::all());
    }

    public function store(Request $request)
    {
        $image    = $this->uploadImage(request()->image);
        $product  = new Product([
            'category_id' => $request->get('category_id'),
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
            'price'       => $request->get('price'),
            'image'       => $image,
        ]);

        $product->save();

        return response()->json('successfully added');
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $image   = $product->image;

        if (request()->image !== 'undefined') {
            $image   = $this->uploadImage(request()->image);
        }

        $product->update([
            'category_id' => $request->get('category_id'),
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
            'price'       => $request->get('price'),
            'image'       => $image,
        ]);

        return response()->json('successfully updated');
    }

    public function delete($id)
    {
        $product = Product::find($id);

        $product->delete();

        return response()->json('successfully deleted');
    }

    private function uploadImage($image)
    {
        $imagePath = 'img/products';
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        request()->image->move(public_path($imagePath), $imageName);

        return '/' . $imagePath . '/' . $imageName;
    }
}
