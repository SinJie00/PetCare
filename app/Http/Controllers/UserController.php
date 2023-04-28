<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'status' => true,
                'message' => "Login successfully"
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => "Login failed"
        ]);
    }

    public function register(Request $request)
    {
        $input = $request->all();

        User::create([
            'name' => $input['name'],
            'email'=> $input['email'],            
            'password' => Hash::make($input['password']),
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'address' => $input['address'],
        ]);

        return response()->json(['status'=>true, 'message' => 'Register Successfully']);
    }

    /* public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)) {
            $success = true;
            $message = "User login successfully";
        } else {
            $success = false;
            $message = "Unautorised";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    } */


    /* public function register(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $success = true;
            $message = "Register successfully";

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);

    } */
}
