<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response($categories, 201);
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
            'name' => ['required', 'max:255']
        ]);

        $category = Category::create($request->all());

        if(!$category){
            $response = [
                'message' => 'Category creation failed',
            ];
    
            return response($response, 417);
        }else{

            $response = [
                'message' => 'Category created successfully',
                'product' => $category,
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
    public function show(Category $category)
    {
        if(!$category){
            $response = [
                'message' => 'Category not found',
            ];
    
            return response($response, 404);
        }

        $response = [
            'message' => 'Category found successfully',
            'product' => $category,
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
    public function update(Request $request, Category $category)
    {
        if(!$category){
            $response = [
                'message' => 'Category not found',
            ];
    
            return response($response, 404);
        }
        
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $updatedCategory = $category->update($request->all());

        if(!$updatedCategory){
            $response = [
                'message' => 'Category update fail',
            ];
    
            return response($response, 417);

        }else{

            $response = [
                'message' => 'Category updated successfully',
                'product' => $updatedCategory,
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
    public function destroy(Category $category)
    {
        if(!$category){
            $response = [
                'message' => 'Category delettion fail',
            ];
    
            return response($response, 404);

        }else{
            $category->delete();

            $response = [
                'message' => 'Category delettion complete',
            ];
    
            return response($response, 202);
        }
    }
}
