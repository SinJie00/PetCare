<?php

namespace App\Http\Controllers;

use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VolunteerApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volunteerApplications = VolunteerApplication::all();
        return response()->json($volunteerApplications);
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
            'volunteer_type' => 'required',
        ]);

        // Check if the user has a previous pending or approved application of the same type
        $existingApplication = VolunteerApplication::where('users_id', $validatedData['users_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('volunteer_type', $validatedData['volunteer_type'])
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You have an existing application of the same type. You should not submit the same request again.',
            ], 400);
        }

        // Check if the user has a previous rejected application
        $rejectedApplication = VolunteerApplication::where('users_id', $validatedData['users_id'])
            ->where('status', 'rejected')
            ->where('volunteer_type', $validatedData['volunteer_type'])
            ->first();

        if ($rejectedApplication) {
            // Check if a specific time period has passed since the rejection
            $allowedDuration = 7; // Adjust the duration as per your requirements
            $rejectionDate = $rejectedApplication->application_date;
            $currentDate = Carbon::now();
            $daysSinceRejection = $currentDate->diffInDays($rejectionDate);

            if ($daysSinceRejection <= $allowedDuration) {
                return response()->json([
                    'message' => 'You have a rejected application within the allowed duration which is 1 week. Please try again later.',
                ], 400);
            }
        }

        /*$volunteerApplication = new VolunteerApplication();
        $volunteerApplication->users_id = $validatedData['users_id'];
        $volunteerApplication->volunteer_type = $validatedData['volunteer_type'];
        $volunteerApplication->application_date = Carbon::now();
        $volunteerApplication->save();*/
        return response()->json([
            'message' => 'Volunteer application submitted successfully',
            'data' => '$volunteerApplication'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VolunteerApplication $volunteerApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VolunteerApplication $volunteerApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adoptionApplication = VolunteerApplication::findOrFail($id);
        $adoptionApplication->delete();
        return response()->json(['message' => 'Volunteer application deleted successfully']);
    }

    public function approveVolunteerApplication($id)
    {
        $timestamps = false;
        $application = VolunteerApplication::findOrFail($id);

        // Update volunteer application status to "approved" & save approval datetime
        $application->status = 'Approved';
        $application->approval_date = Carbon::now()->format('Y-m-d H:i:s');
        $application->save();

        return response()->json(['message' => 'Volunteer application has been approved.', 'application' => $application]);
    }

    public function rejectVolunteerApplication($id)
    {
        $application = VolunteerApplication::findOrFail($id);

        // Update adoption application status to "rejected"
        $application->status = 'Rejected';
        $application->approval_date = Carbon::now()->format('Y-m-d H:i:s');;
        $application->save();

        return response()->json(['message' => 'Volunteer application has been rejected.', 'application' => $application]);
    }
}
