<?php

namespace App\Http\Controllers;

use App\Events\ProductReviewed;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $review = Review::latest()->paginate( 5 );

        return response()->json($review, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        if(!$review){
            $response = [
                "message" => "Review not found",
            ];
    
            return response()->json($response, 404);
        }

        $response = [
            "message" => "Review found successfully",
            "review" => $review,
        ];

        return response()->json($response, 302);
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
            'email'=> ['required','email'],
            'description' => ['required'],
            'product_id' => ['required'],
        ]);

        $review = Review::create($request->all());

        if(!$review){
            $response = [
                "message" => "Review submittion failed",
            ];
    
            return response()->json($response, 417);
        }else{

            $response = [
                "message" => "Review submitted successfully",
                "review" => $review,
            ];

            // Fire this event to get notify the Admin
            if(env('MAIL_USERNAME') !== null && env('MAIL_PASSWORD') !== null)
            {
                event(new ProductReviewed($review));
            }
    
            // return response()->json($response, 201);
        }
    }

    public function reviewStatus(Review $review)
    {   
        if($review->status){
            $status = 0;
        }else{
            $status = 1;
        }

        $review->status = $status;
        $review->save();

        $response = [
            "message" => "Review updated successfully",
            "review" => $review,
        ];

        return response()->json($response, 201);
    }


}
