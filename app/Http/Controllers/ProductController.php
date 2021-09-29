<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTag;
use App\Models\Review;
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
        $product = Product::with(['product_images','tags'])
                            ->latest()
                            ->paginate( 5 );

        $response = [
            "message" => "Products found successfully",
            "product" => $product,
        ];

        return response()->json($response, 200);
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
            'images.*' => ['required','mimes:jpeg,jpg,png,gif','max:10000'],
            'tag.*' => ['required', 'integer']
        ]);
        
        $product = Product::create($request->all());

        if(!$product){
            $response = [
                "message" => "Product creation failed",
            ];
    
            return response()->json($response, 417);

        }else{
            
            // Upload multiple image
            $imageResponse = $this->commonImageUpload( $request, $product);

            // Insert product's tag into pivot table
            $product->tags()->attach($request->tag);

            $response = [
                "message" => "Product created successfully",
                "product" => $product,
            ];

            $response = array_merge($response, $imageResponse);
    
            return response()->json($response, 201);
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
                "message" => "Product not found",
            ];
    
            return response()->json($response, 404);
        }

        $response = [
            "message" => "Product found successfully",
            "product" => $product,
        ];

        return response()->json($response, 302);
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
                "message" => "Product not found",
            ];
    
            return response()->json($response, 404);
        }
        
        $request->validate([
            'name' => ['required', 'max:255'],
            'slug' => ['required'],
            'category_id' => ['required', 'integer'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'images.*' => ['mimes:jpeg,jpg,png,gif','max:10000'],
            'tag.*' => [ 'integer']
        ]);

        $updatedProduct = $product->update($request->all());

        if(!$updatedProduct){
            $response = [
                "message" => "Product update fail",
            ];
    
            return response()->json($response, 417);

        }else{
        
            // Upload multiple image
            if($request->file( 'images' )){
                $imageResponse = $this->commonImageUpload( $request, $product );
            }

            // Insert product's tag into pivot table
            if($request->tag){
                $product->tags()->sync($request->tag);
            }

            $response = [
                "message" => "Product updated successfully",
                "product" => $updatedProduct,
            ];

            if(!empty($imageResponse)){
                $response = array_merge($response, $imageResponse);
            }
    
            return response()->json($response, 202);
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
                "message" => "Product delettion fail",
            ];
    
            return response()->json($response, 404);

        }else{

            $product->productImages()->delete();
            $product->delete();

            $response = [
                "message" => "Product delettion complete",
            ];
    
            return response()->json($response, 202);
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


    /**
     * Display a listing of the resource without authenticate.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProducts()
    {   
        $product = Product::with(['category','tags'])
                            ->latest()
                            ->paginate( 5 );

        $response = [
            "message" => "Products found successfully",
            "product" => $product,
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productDetails($id)
    {
        $product = Product::with(['category','tags','product_images','approvedReviews'])
                            ->findOrFail($id);

        $response = [
            "message" => "Product found successfully",
            "product" => $product,
        ];

        return response()->json($response, 302);
    }




    private function commonImageUpload( $request, $product )
    {   
        if(empty($request->file( 'images' ))){
            $response = [
                "images" => "No image found to upload",
            ];

            return $response;
        }

        $data = null;
        
        // Check for Product's image folder exit or not
        if (!file_exists($this->productImagePath)) {
            mkdir($this->productImagePath, 666, true);
        }

        $i = 1;
        foreach($request->file( 'images' ) as $image){

            // Processing image
            $fileExtention = $image->getClientOriginalExtension();
            $fileName = date( "Ymdhis_$i." ) . $fileExtention;

            // Save image in product directory
            $imageUploadResponse = Image::make( $image )->save( $this->productImagePath . $fileName );

            // Push into array for saving all together
            $temp = null;
            $temp['product_id'] = $product->id;
            $temp['image'] = $fileName;

            if ($request->isMethod('post')) {
                $temp['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
            }

            $temp['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();

            $data[] = $temp;
            $i++;
        }

        // Save all image 
        
        // ProductImage::insert($data);
        $product->product_images()->createMany($data);

        $response = [
            "images_message" => "Image upload successfully",
            "image_status" => 202
        ];

        return $response;
    }

    
}
