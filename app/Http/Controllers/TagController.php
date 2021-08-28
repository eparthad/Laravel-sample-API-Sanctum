<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return response()->json($tags, 201);
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

        $tag = Tag::create($request->all());

        if(!$tag){
            $response = [
                "message" => "Tag creation failed",
            ];
    
            return response()->json($response, 417);
        }else{

            $response = [
                "message" => "Tag created successfully",
                "tag" => $tag,
            ];
    
            return response()->json($response, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        if(!$tag){
            $response = [
                "message" => "Tag not found",
            ];
    
            return response()->json($response, 404);
        }

        $response = [
            "message" => "Tag found successfully",
            "tag" => $tag,
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
    public function update(Request $request, Tag $tag)
    {
        if(!$tag){
            $response = [
                "message" => "Tag not found",
            ];
    
            return response()->json($response, 404);
        }
        
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $updatedTag = $tag->update($request->all());

        if(!$updatedTag){
            $response = [
                "message" => "Tag update fail",
            ];
    
            return response()->json($response, 417);

        }else{

            $response = [
                "message" => "Tag updated successfully",
                "tag" => $updatedTag,
            ];
    
            return response()->json($response, 202);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if(!$tag){
            $response = [
                "message" => "Tag delettion fail",
            ];
    
            return response()->json($response, 404);

        }else{
            $tag->delete();

            $response = [
                "message" => "Tag delettion complete",
            ];
    
            return response()->json($response, 202);
        }
    }
}
