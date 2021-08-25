<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Image;

class ProductController extends Controller
{

    private $productImagePath;

    public function __construct()
    {
        $this->productImagePath = config( 'constants.all_image_path.products_image_path' );
    }

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

            // Upload multiple image
            $imageResponse = $this->commonImageUpload( $request, $product->id );

            $response = [
                'message' => 'Product created successfully',
                'product' => $product,
            ];

            $response = array_merge($response, $imageResponse);
    
            return response($response, 201);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if(!$product){
            $response = [
                'message' => 'Product not found',
            ];
    
            return response($response, 404);
        }

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
        
            // Upload multiple image
            $imageResponse = $this->commonImageUpload( $request, $product->id );

            $response = [
                'message' => 'Product updated successfully',
                'product' => $updatedProduct,
            ];

            $response = array_merge($response, $imageResponse);
    
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
                'message' => 'Product delettion fail',
            ];
    
            return response($response, 404);

        }else{

            $product->productImages()->delete();
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


    private function commonImageUpload( $request, $productId )
    {
        if(empty($request->images)){
            $response = [
                'images' => 'No image found to upload',
            ];
        }

        $data = null;
        $allowedfileExtension=['pdf','jpg','png'];

        foreach($request->file( 'images' ) as $image){

            // Processing image
            $fileExtention = $image->getClientOriginalExtension();
            $fileName = date( 'Ymdhis.' ) . $fileExtention;
            $imageUploadResponse = Image::make( $image )->save( $this->productImagePath . $fileName );

            // Push into array for saving all together
            if(in_array($fileExtention,$allowedfileExtension)){
                $temp = null;
                $temp['product_id'] = $productId;
                $temp['image'] = $fileName;
    
                $data[] = $temp;
            }else {
                $response = [
                    'images_message' => 'Invalid file format',
                    'image_status' => 422
                ];

                return $response;
            }
            
        }

        ProductImage::insert($data);
        $response = [
            'images_message' => 'Iamage upload successfully',
            'image_status' => 202
        ];

        return $response;

    }

    
}
