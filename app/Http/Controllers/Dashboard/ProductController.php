<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view product');
        try {
            $products = Product::latest()->get();
            return view('dashboard.products.index', compact('products'));
        } catch (\Throwable $th) {
            Log::error("Product Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create product');
        try {
            return view('dashboard.products.create');
        } catch (\Throwable $th) {
            Log::error("Product Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create product');
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'required|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max_size',
            'price' => 'required|string|max:255',
            'reviews_count' => 'required|integer|min:0',
            'rating' => 'required|numeric|min:1|max:5',
            'is_popular' => 'nullable|in:on',
        ]);

        if ($validator->fails()) {
            Log::error('Product Store Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $product = new Product();
            $product->category = $request->category;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->description = $request->description;
            if ($request->hasFile('main_image')) {
                $profileImage = $request->file('main_image');
                $profileImage_ext = $profileImage->getClientOriginalExtension();
                $profileImage_name = time() . '_mainImage.' . $profileImage_ext;

                $profileImage_path = 'uploads/main-images';
                $profileImage->move(public_path($profileImage_path), $profileImage_name);
                $product->main_image = $profileImage_path . "/" . $profileImage_name;
            }
            $product->price = $request->price;
            $product->reviews_count = $request->reviews_count;
            $product->rating = $request->rating;
            $product->is_popular = $request->is_popular == 'on' ? '1' : '0';
            $product->save();

            DB::commit();
            return redirect()->route('dashboard.products.index')->with('success', 'Product Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Product Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view product');

        try {
            $product = Product::findOrFail($id);
            return view('dashboard.products.show', compact('product'));
        } catch (\Throwable $th) {
            Log::error("Product Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update product');
        try {
            $product = Product::findOrFail($id);
            return view('dashboard.products.edit', compact('product'));
        } catch (\Throwable $th) {
            Log::error("Product Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update product');
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $id,
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'description' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max_size',
            'price' => 'required|string|max:255',
            'reviews_count' => 'required|integer|min:0',
            'rating' => 'required|numeric|min:1|max:5',
            'is_popular' => 'nullable|in:on',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->category = $request->category;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->description = $request->description;
            if ($request->hasFile('main_image')) {
                if (isset($product->main_image) && File::exists(public_path($product->main_image))) {
                    File::delete(public_path($product->main_image));
                }

                $mainImage = $request->file('main_image');
                $mainImage_ext = $mainImage->getClientOriginalExtension();
                $mainImage_name = time() . '_mainImage.' . $mainImage_ext;

                $mainImage_path = 'uploads/main-images';
                $mainImage->move(public_path($mainImage_path), $mainImage_name);
                $product->main_image = $mainImage_path . "/" . $mainImage_name;
            }
            $product->price = $request->price;
            $product->reviews_count = $request->reviews_count;
            $product->rating = $request->rating;
            $product->is_popular = $request->is_popular == 'on' ? '1' : '0';
            $product->save();

            DB::commit();
            return redirect()->route('dashboard.products.index')->with('success', 'Product Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Product Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete product');
        try {
            $product = Product::findOrFail($id);
            if (isset($product->main_image) && File::exists(public_path($product->main_image))) {
                File::delete(public_path($product->main_image));
            }
            $product->delete();
            return redirect()->back()->with('success', 'Product Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Product Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update product');
        try {
            $product = Product::findOrFail($id);
            $message = $product->is_active == 'active' ? 'Product Deactivated Successfully' : 'Product Activated Successfully';
            if ($product->is_active == 'active') {
                $product->is_active = 'inactive';
                $product->save();
            } else {
                $product->is_active = 'active';
                $product->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Product Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
