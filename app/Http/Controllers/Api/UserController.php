<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function search(Request $request)
    {
        try {
            $users = User::select('id', 'email', 'username', 'role_id')->where('role_id', 2)
                ->whereNull('closed_on')
                ->when(!is_null($request->get('search')), function ($q) use ($request) {
                    return $q->where(function ($q) use ($request) {
                        $q->where('username', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                            ->orWhereHas('profile', function($q) use ($request) {
                                return $q->where('first_name', 'like', "%{$request->search}%")
                                    ->orWhere('last_name', 'like', '%' . $request->search . '%');
                            });
                    });
                })
                ->latest()
                ->simplePaginate(10)
                ->through(function ($user, $key) {
                    $user = get_user_profile($user->id);
                    return $user;
                });

            return response()->json(array_merge([ 'success' => true ], $users->toArray()), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }
}
