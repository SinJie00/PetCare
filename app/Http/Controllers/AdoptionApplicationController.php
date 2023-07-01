<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\AdoptionAnimal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdoptionApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adoptionApplications = AdoptionApplication::all();
        return response()->json($adoptionApplications);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'users_id' => 'required',
            'adoption_animals_id' => 'required',
        ]);

        // Check if the user has a previous pending or approved application of the same type
        $existingApplication = AdoptionApplication::where('users_id', $validatedData['users_id'])
            ->where('status', 'Pending')
            ->where('adoption_animals_id', $validatedData['adoption_animals_id'])
            ->latest('application_date')
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You have an existing application for this animal. You should not submit the same request again.',
            ], 400);
        }

        // Check if the user has an approved application of the same type
        $approvedApplication = AdoptionApplication::where('users_id', $validatedData['users_id'])
            ->where('status', 'Approved')
            ->where('adoption_animals_id', $validatedData['adoption_animals_id'])
            ->latest('application_date')
            ->first();

        if ($approvedApplication) {
            return response()->json([
                'message' => 'Your existing application for this animal has already been approved. You should not submit the same request again.',
            ], 400);
        }

        // Check if the user has a previous rejected application
        $rejectedApplication = AdoptionApplication::where('users_id', $validatedData['users_id'])
            ->where('status', 'Rejected')
            ->where('adoption_animals_id', $validatedData['adoption_animals_id'])
            ->latest('application_date')
            ->first();

        if ($rejectedApplication) {
            $allowedDuration = 7; // Adjust the duration as per your requirements
            $rejectionDate = Carbon::parse($rejectedApplication->application_date);
            $currentDate = Carbon::now();
            $daysSinceRejection = $currentDate->diffInDays($rejectionDate);

            if ($daysSinceRejection < $allowedDuration) {
                $remainingDays = $allowedDuration - $daysSinceRejection;
                return response()->json([
                    'message' => 'You have a rejected application for this animal within the allowed duration. You can reapply in ' . $remainingDays . ' day.',
                ], 400);
            }
            /* else{
                return response()->json([
                    'message' => 'You have a ,ekeke'. $daysSinceRejection .'and'.$rejectionDate .'and'.$currentDate,
                ], 400);
            } */
        }

        $adoptionApplication = new AdoptionApplication();
        $adoptionApplication->users_id = $validatedData['users_id'];
        $adoptionApplication->adoption_animals_id = $validatedData['adoption_animals_id'];
        $adoptionApplication->application_date = Carbon::now()->format('Y-m-d H:i:s');
        $adoptionApplication->save();

        return response()->json([
            'message' => 'Adoption application submitted successfully',
            'data' => $adoptionApplication
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdoptionApplication $adoptionApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdoptionApplication $adoptionApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdoptionApplication $adoptionApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adoptionApplication = AdoptionApplication::findOrFail($id);
        $adoptionApplication->delete();
        return response()->json(['message' => 'Adoption application deleted successfully']);
    }

    public function approveAdoptionApplication($id)
    {
        $application = AdoptionApplication::findOrFail($id);
        $animal = AdoptionAnimal::findOrFail($application->adoption_animals_id);

        // Update adoption application status to "approved" & save approval datetime
        $application->status = 'Approved';
        $application->approval_date = Carbon::now()->format('Y-m-d H:i:s');
        $application->save();

        // Update animal status to "adopted"
        $animal->status = 'Adopted';
        $animal->save();

        return response()->json(['message' => 'Adoption application has been approved.', 'application'=>$application]);
    }

    public function rejectAdoptionApplication($id)
    {
        $application = AdoptionApplication::findOrFail($id);
        $animal = AdoptionAnimal::findOrFail($application->adoption_animals_id);

        // Update adoption application status to "rejected"
        $application->status = 'Rejected';
        $application->approval_date = Carbon::now()->format('Y-m-d H:i:s');
        $application->save();

        // Update animal status to "Available"
        $animal->status = 'Available';
        $animal->save();

        return response()->json(['message' => 'Adoption application has been rejected.', 'application'=>$application]);
    }
}
