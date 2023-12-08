<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string|max:1000',
            'marital_status' => 'nullable|in:married,single',
            'gender' => 'nullable|in:Male,Female',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => [ 'nullable', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')->ignore(auth('api')->user()->id) ],
            'username' => 'nullable|unique:users,username,' . auth('api')->user()->id,
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
                'errors' => $validator->errors()
            ], 401);
        }

        try {
            $user = auth('api')->user();
            if (collect($request)->has('email')) {
                $user->email = $request['email'];
                $user->save();
            }
            if (collect($request)->has('username')) {
                $user->username = $request['username'];
                $user->save();
            }

            //change password
            if (collect($request)->has('password') && !is_null($request->get('password'))) {
//                if(!Hash::check($request->oldpass, $user->password)) {
//                    return response()->json([
//                        'success' => false,
//                        'message' => 'Incorrect old password',
//                        'errors' => []
//                    ], 401);
//                }
                $user->password = Hash::make($request->password);
                $user->pwh = $request->password;
                $user->save();
            }

            $user->profile()->update(
                collect($request)->except(['email', 'username', 'preferred_payout_method', 'password', 'password_confirmation'])->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data' => get_user_profile(auth('api')->user()->id ?? null),
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }
}
