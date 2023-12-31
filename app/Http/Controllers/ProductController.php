<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Color;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.pages.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('title', 'id')->toarray();
        $colors = Color::pluck('name', 'id')->toarray();
        return view('admin.pages.products.create', compact('categories', 'colors'));
    }

    public function store(ProductStoreRequest $request)
    {

        //upload image
        $image = time() . '-' . $request->title . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $image);
        // try {
        $product = Product::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'sku_number' => $request->sku_number,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $image,
            'is_active' => $request->is_active ?? 0,
        ]);
        $product->colors()->attach($request->color_id);

        // Session::flash('status', 'Product Inserted Successfully');

        return redirect()->route('products.index')->withStatus('Product Inserted Successfully');

        // } catch (QueryException $e) {
        //     Log::error($e->getMessage());
        //     return redirect()->route('products.create')->withErrors($e->getMessage())->withInput();
        // }

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|min:100',
        // ]);

        // if ($validator->fails()) {
        //     return redirect('products/create')
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        // $validated = $request->validate([
        //     'title' => 'required',
        //     'price' => 'required',
        // ]);


        // dd($request->all());
        // dd($request->title);
        // dd($request->only('title', 'price'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.pages.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::pluck('title', 'id')->toarray();
        $colors = Color::pluck('name', 'id')->toarray();
        $selectedColorId = $product->colors()->pluck('id')->toarray();
        // dd($selectedColorId);
        return view('admin.pages.products.edit', compact('product', 'categories', 'colors', 'selectedColorId'));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try {

            $product = Product::findOrFail($id);
            $product->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'description' => $request->description,
                'sku_number' => $request->sku_number,
                'price' => $request->price,
                'is_active' => $request->is_active ?? 0,
            ]);
            $product->colors()->sync($request->color_id);

            // Session::flash('status', 'Product Updated Successfully');
            return redirect()->route('products.index')->withStatus('Product Updated Successfully');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {

        Product::destroy($id);
        return redirect()->route('products.index')->withStatus('Product Deleted Successfully');
    }

    public function trash()
    {
        $this->authorize('product-trash-list');
        $products = Product::latest()->onlyTrashed()->paginate(10);
        return view('admin.pages.products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        $product->restore();
        return redirect()->route('products.trash')->withStatus('Product restored Successfully');
    }

    public function delete($id)
    {
        $product = Product::onlyTrashed()->find($id);
        $product->colors()->detach();
        $product->forceDelete();
        return redirect()->route('products.trash')->withStatus('Product Deleted Successfully');
    }

    public function downloadPdf()
    {

        $products = Product::latest()->take(10)->get();
        $pdf = Pdf::loadView('admin.pages.products.pdf', compact('products'));
        return $pdf->download('product-list.pdf');
    }

    public function chekout(Request $request)
    {
        $request->status;
        $request->user_id;
        $request->full_name;
        $request->contact_number;
        $request->shipping_address;
        dd(request()->all());
    }
}
