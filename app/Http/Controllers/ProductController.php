<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $filePath = $this->fileUpload($request);

        $product = new Product();
        $product->name = $request->name;
        $product->image = $filePath;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->save();

        return redirect('/products')->with('completed', 'Product has been saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('product.create', compact('categories','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'sometimes|image|mimes:jpeg,jpg,png|max:2048',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if($request->has('image')) {
            $filePath = $this->fileUpload($request);
        }

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        if($request->has('image')) {
            $product->image = $filePath;
        }
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->save();

        return redirect('/products')->with('completed', 'Product has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect('/products')->with('completed', 'Product has been deleted');
    }

    /**
     *  Upload image file
     */    
    public function fileUpload($request) {
        $fileName = time().'.'.$request->image->extension();
        $filePath = public_path('uploads');
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $request->image->move($filePath, $fileName);
        return 'uploads/'.$fileName;
    }
}
