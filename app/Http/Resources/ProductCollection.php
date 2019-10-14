<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->collection as $key => $product) {
            $this->collection[$key]->category = Category::find($product->category_id);
        }

        return [
            'data' => $this->collection
        ];
    }
}
