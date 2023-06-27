<?php

namespace App\Http\Controllers;

use App\Models\StrayPost;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class StrayPostController extends Controller
{
    public function index()
    {
        /* $strayPosts = StrayPost::with('user')->get(); */
        $strayPosts = StrayPost::all();
        return response()->json($strayPosts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'users_id' => 'required',
        ]);

        // Handle the image upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/stray_posts'), $imageName);

        /* $data = $request->only(['title', 'description', 'image', 'location']);
        $data['user_id'] = auth()->user()->id; */

        // Create the Stray Post record
        $data = $request->all();
        $data['image'] = '/images/stray_posts/' . $imageName;

        $strayPost = StrayPost::create($data);

        return response()->json($strayPost);

        /* return response()->json([
            'message' => 'Stray post created successfully',
            'strayPost' => $strayPost
        ], 201); */

        
    }

    public function show($id)
    {
        $strayPost = StrayPost::findOrFail($id);
        return response()->json(['strayPost' => $strayPost]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::debug(print_r($request->all(), true));
        
        $strayPost = StrayPost::findOrFail($id);
        $strayPost->title = $request->input('title');
        $strayPost->description = $request->input('description');
        $strayPost->location = $request->input('location');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImagePath = public_path('images/stray_posts/' . $strayPost->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/stray_posts'), $imageName);
            $newImageFile = '/images/stray_posts/' . $imageName;
            $strayPost->image = $newImageFile;
        }

        $strayPost->save();

        return response()->json([
            'message' => 'Stray post updated successfully',
            'strayPost' => $strayPost
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $strayPost= StrayPost::findOrFail($id);
        $strayPost->delete();
        return response()->json(['message' => 'Stray Post deleted successfully']);
    }

}
