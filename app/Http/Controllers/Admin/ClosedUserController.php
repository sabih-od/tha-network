<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClosedUserController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(User::with('profile')->whereNotNull('closed_on')->where('role_id', 2)->get())
                    ->addIndexColumn()
                    ->editColumn('created_at', function($data){
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('m-d-Y');
                        return $formatedDate;
                    })
                    ->editColumn('first_name', function($data){
                        return $data->profile->first_name ?? '';
                    })
                    ->editColumn('last_name', function($data){
                        return $data->profile->last_name ?? '';
                    })
                    ->addColumn('profile_picture', function ($data) {
                        return '<img width="40" src="'.$data->get_profile_picture().'"></img>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<button title="Retrieve Closed Account" type="button" name="retrieve closed account" id="' . $data->id . '" class="delete btn btn-success btn-sm"><i class="fa fa-recycle"></i></button>';
                    })->rawColumns(['profile_picture', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.closed-user.list');
    }

    final public function retrieve($id)
    {
        $content=User::where('id', $id)->first();
        $content->closed_on = null;
        $content->save();

        toggle_user_subscription($content->id, false, true);

        echo 1;
    }
}
