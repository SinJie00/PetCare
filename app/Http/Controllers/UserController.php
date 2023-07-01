<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    use HasApiTokens;

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
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
        } else {
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

    public function rules(Request $request, $user = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)], /* Rule::unique('users')->ignore($this->user)], */
            'gender' => ['required', Rule::in(['M', 'F'])],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9]{3}-[0-9]{7}$/',Rule::unique('users')->ignore($user)],
            'address' => 'required|string|max:255',
        ];

        if ($request->has('password') || $request->has('confirm_password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'];
            $rules['confirm_password'] = 'required_with:password|same:password';
        }

        return $rules;
    }

    public function register(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email.unique' => 'The email address is already registered.',
            'phone.regex' => 'The valid :attribute number format must be 01x-xxxxxxxx.',
            'phone.unique'=> 'The phone number is already registered.',
            'password.min' => 'The :attribute must be at least :min characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            'confirm_password.same' => 'The confirmation password does not match.',
        ];

        $validator = Validator::make($request->all(), $this->rules($request), $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $input = $request->only(['name', 'email', 'gender', 'phone', 'address', 'password', 'confirm_password']);

        //Log::debug('confirm_password: ' . $input['confirm_password']); // add this line to log the confirm_password value

        // check if password and confirm_password fields match
        /* if ($input['password'] !== $input['confirm_password']) {
            return response()->json(['errors' => ['confirm_password' => ['The password field confirmation does not match.']]], 422);
        } */

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'password' => bcrypt($input['password']),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function updateProfile(Request $request, User $user)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email.unique' => 'The email address is already registered.',
            'phone.regex' => 'The valid :attribute number format must be 01x-xxxxxxxx.',
            'phone.unique'=> 'The phone number is already registered.',
        ];

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /*  Log::info('User information: ' . print_r($user, true)); */
        $validator = Validator::make($request->all(), $this->rules($request, $user), $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address')
        ]);

        return response()->json(['message' => 'Update Profile Successfully', 'user' => $user]);
    }
    public function getUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /*forgot password*/
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);        
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset password link sent to your email.'])
            : response()->json(['message' => 'Failed to send reset password link.'], 500);
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'],
            'confirm_password' => 'required_with:password|same:password',
        ];

        $messages = [
            'required' => 'The password field is required.',
            'password.min' => 'The :attribute must be at least :min characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            'confirm_password.same' => 'The confirmation password does not match.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = Password::reset($request->only('email', 'password', 'confirm_password', 'token'), function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been successfully reset.']);
        } else {
            return response()->json(['message' => 'Failed to reset password.'], 500);
        }
    }
    
}
