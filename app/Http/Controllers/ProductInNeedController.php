<?php

namespace App\Http\Controllers;

use App\Models\ProductInNeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductInNeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductInNeed::all();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Add validation rules for other fields
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/products'), $imageName);

        // Create the Product record
        $data = $request->all();
        $data['image'] = '/images/products/' . $imageName;
        $product = ProductInNeed::create($data);

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductInNeed $productInNeed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = ProductInNeed::findOrFail($id);
        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = ProductInNeed::findOrFail($id);
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->required_quantity = $request->input('required_quantity');
        $product->remain_quantity = $request->input('remain_quantity');
        $product->link = $request->input('link');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImagePath = public_path('images/products/' . $product->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $newImageFile = '/images/products/' . $imageName;
            $product->image = $newImageFile;
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product= ProductInNeed::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
