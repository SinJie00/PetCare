<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HasApiTokens;
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Login unsuccessfully. The email or password is incorrect.'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        /* $request->user()->tokens()->delete(); */
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function rules():array
    {
        return [
            'name' => 'required|string|max:255',
            /* 'name' => ['required','string','max:255'], */
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => ['required','string','min:8',/*'confirmed', 'same:confirm_password', */'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'],
            'confirm_password' => 'required_with:password|same:password',
        ];
    }
    public function register(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email.unique' => 'The email address is already registered.',
            'password.min' => 'The :attribute must be at least :min characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            'confirm_password.same' => 'The confirmation password does not match.',
        ];
            
        $validator = Validator::make($request->all(), $this->rules(), $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $input = $request->only(['name', 'email', 'gender', 'phone', 'address', 'password', 'confirm_password']);

        Log::debug('confirm_password: ' . $input['confirm_password']); // add this line to log the confirm_password value

        // check if password and confirm_password fields match
        /* if ($input['password'] !== $input['confirm_password']) {
            return response()->json(['errors' => ['confirm_password' => ['The password field confirmation does not match.']]], 422);
        } */

        $user = User::create([
            'name' => $input['name'],
            'email'=> $input['email'],            
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'password' => bcrypt($input['password']),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }
}
