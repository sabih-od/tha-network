<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\User;
use Carbon\Carbon;
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
                ->through(function ($item, $key) {
                    return get_user_profile($item->id, false);
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

    public function newMembersThisWeek(Request $request)
    {
        try {
            $users = User::select('id', 'email', 'username', 'role_id')->where('role_id', 2)
                ->whereNull('closed_on')
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->orderBy('created_at', 'DESC')
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
                ->through(function ($item, $key) {
                    return get_user_profile($item->id, false);
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

    public function peopleInMyNetwork (Request $request)
    {
        try {
            $network = Network::where('user_id', auth('api')->id())->whereHas('user', function($q) {
                return $q->whereNull('closed_on');
            })->first();
            $network_members = $network ? $network->members()->get() : [];
            $network_member_ids = [];
            foreach ($network_members as $network_member) {
                array_push($network_member_ids, $network_member->user_id);
            }

            $users = User::select('id', 'email', 'username', 'role_id')->where('role_id', 2)
                ->whereIn('id', $network_member_ids)
                ->whereNull('closed_on')
                ->orderBy('created_at', 'DESC')
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
                ->through(function ($item, $key) {
                    return get_user_profile($item->id, false);
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
