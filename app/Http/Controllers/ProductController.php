<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductCollection;

use App\Models\Category;
use App\Http\Resources\CategoryCollection;

use App\Http\Requests\StoreProduct;

use Illuminate\Support\Facades\File;
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

    public function store(StoreProduct $request)
    {
        $request->validated();

        $image = null;
        if (request()->image) {
            $image = $this->uploadImage(request()->image);
        }

        $product   = new Product([
            'category_id' => $request->get('category_id'),
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
            'price'       => $request->get('price'),
            'image'       => $image,
        ]);

        $product->save();

        return response()->json('Successfully added');
    }

    public function update($id, StoreProduct $request)
    {
        $request->validated();

        $product = Product::find($id);
        $image   = $product->image;

        if (request()->image) {
            $image = $this->uploadImage(request()->image);

            if(File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
        }

        $product->update([
            'category_id' => $request->get('category_id'),
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
            'price'       => $request->get('price'),
            'image'       => $image,
        ]);

        return response()->json('Successfully updated');
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if(File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return response()->json('Successfully deleted');
    }

    private function uploadImage($image)
    {
        $imagePath = '/img/products';
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        request()->image->move(public_path($imagePath), $imageName);

        return $imagePath . '/' . $imageName;
    }
}
