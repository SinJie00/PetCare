<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\AdoptionAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdoptionAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adoptionAnimals = AdoptionAnimal::all();
        return response()->json($adoptionAnimals);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /* return view('adoptionanimal.create'); */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Add validation rules for other fields
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/adoption_animals'), $imageName);

        // Create the AdoptionAnimal record
        $data = $request->all();
        $data['image'] = '/images/adoption_animals/' . $imageName;
        $adoptionAnimal = AdoptionAnimal::create($data);

        return response()->json($adoptionAnimal);

        /* $adoptionAnimal = AdoptionAnimal::create($request->all());
        return response()->json($adoptionAnimal); */
        /*  return response()->json($adoptionAnimal, 201); */
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        /* $adoptionAnimal = AdoptionAnimal::findOrFail($id);
        return response()->json($adoptionAnimal); */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $animal = AdoptionAnimal::findOrFail($id);
        return response()->json(['animal' => $animal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //Log::debug(print_r($request->all(), true));

        $animal = AdoptionAnimal::findOrFail($id);
        $animal->name = $request->input('name');
        $animal->age = $request->input('age');
        $animal->gender = $request->input('gender');
        $animal->type = $request->input('type');
        $animal->status = $request->input('status');
        $animal->description = $request->input('description');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImagePath = public_path('images/adoption_animals/' . $animal->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/adoption_animals'), $imageName);
            $newImageFile = '/images/adoption_animals/' . $imageName;
            $animal->image = $newImageFile;
            /* $data['image'] = $newImageFile; */
        }

        $animal->save();

        return response()->json([
            'message' => 'Animal updated successfully',
            'animal' => $animal
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adoptionAnimal = AdoptionAnimal::findOrFail($id);
        $adoptionAnimal->delete();
        return response()->json(['message' => 'Adoption animal deleted successfully']);
    }

    public function getAvailableAnimals()
    {
        $animals = AdoptionAnimal::where('status', 'Available')->get();
        return response()->json($animals);
    }
}
