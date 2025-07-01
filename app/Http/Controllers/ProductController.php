<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('layouts.product.view', compact('products'));
    }

    public function add()
    {
        return view('layouts.product.add');
    }

    //product add
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = new Product();
            $product->user_id = Auth::user()->id; 
            $product->name = $validated['name'];
            $product->description = $validated['description'];
            $product->price = $validated['price'];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/pro_images/'), $imageName);
                $product->image = 'assets/pro_images/' . $imageName;
            }

            $product->save();

            return redirect()->back()->with('success', 'Product added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found or you do not have permission to edit this product.');
        }
        return view('layouts.product.edit', compact('product'));
    }

    //product update
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);

            $product->name = $validated['name'];
            $product->description = $validated['description'];
            $product->price = $validated['price'];

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/pro_images/'), $imageName);
                $product->image = 'assets/pro_images/' . $imageName;
            }

            $product->save();

            return redirect()->back()->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    //product delete
    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id)->where('user_id', Auth::user()->id)->first();
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();
            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
