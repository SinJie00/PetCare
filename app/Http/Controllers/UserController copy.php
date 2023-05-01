<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
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
            // Hash the user's password and store it in the session
            //$request->session()->put('password', bcrypt($credentials['password']));
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'token' => $token
            ], 200);
            /* return response()->json([
                'status' => true,
                'message' => "Login successfully"
            ]); */
        }
    
        /* return response()->json([
            'status' => false,
            'message' => "Login failed"
        ]); */
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid login credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => ['required|string|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'],
        ];
    }
    public function register(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email.unique' => 'The email address is already registered.',
            'password.min' => 'The :attribute must be at least :min characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            // Add more custom messages here
        ];
        
        $input = $request->only(['name', 'email', 'gender', 'phone', 'address', 'password', 'confirm_password']);
    
        /* $this->validate($request, $this->rules()); */
        $validator = Validator::make($request->all(), $this->rule(), $messages);
        
        /* $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|max:255',
            'gender' => 'required|string|size:1|in:M,F',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]); */

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $input['name'],
            'email'=> $input['email'],            
            'password' => bcrypt($input['password']),
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'address' => $input['address'],
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }


    /* public function register(Request $request)
    {
        $input = $request->all();

        User::create([
            'name' => $input['name'],
            'email'=> $input['email'],            
            'password' => $input['password'],
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'address' => $input['address'],
        ]);

        return response()->json(['status'=>true, 'message' => 'Register Successfully']);
    } */

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
