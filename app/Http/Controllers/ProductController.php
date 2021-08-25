<?php

namespace App\Http\Controllers;

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
        return Product::all();
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
            'name' => ['required', 'max:255'],
            'slug' => ['required'],
            'category_id' => ['required', 'integer'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
        ]);

        $product = Product::create($request->all());

        if(!$product){
            $response = [
                'message' => 'Product creation failed',
            ];
    
            return response($response, 417);

        }else{
            $response = [
                'message' => 'Product created successfully',
                'product' => $product,
            ];
    
            return response($response, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        $response = [
            'message' => 'Product found successfully',
            'product' => $product,
        ];

        return response($response, 302);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    { 
        if(!$product){
            $response = [
                'message' => 'Product not found',
            ];
    
            return response($response, 404);
        }
        
        $request->validate([
            'name' => ['required', 'max:255'],
            'slug' => ['required'],
            'category_id' => ['required', 'integer'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
        ]);

        $updatedProduct = $product->update($request->all());

        if(!$updatedProduct){
            $response = [
                'message' => 'Product update fail',
            ];
    
            return response($response, 417);

        }else{
            $response = [
                'message' => 'Product updated successfully',
                'product' => $updatedProduct,
            ];
    
            return response($response, 202);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(!$product){
            $response = [
                'message' => 'Product update fail',
            ];
    
            return response($response, 404);

        }else{
            $product->delete();

            $response = [
                'message' => 'Product delettion complete',
            ];
    
            return response($response, 202);
        }
    }

    /**
     * Search on name
     *
     * @param  str $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }
}
