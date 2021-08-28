<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
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
            'email'=> ['required'],
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
    
            return response()->json($response, 201);
        }
    }
}
