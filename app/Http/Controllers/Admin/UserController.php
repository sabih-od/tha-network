<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reward;
use App\Models\RewardLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Exception\InvalidRequestException;

class UserController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(User::with('profile')->whereNull('suspended_on')->whereNull('closed_on')->where('role_id', 2)->get())
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
                        return '<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i></button>&nbsp
                                <a title="Close" type="button" name="close" id="' . $data->id . '" href="'.route('admin.user.close', $data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></a>&nbsp
                                <a title="Close" type="button" name="detail" id="' . $data->id . '" href="'.route('admin.user.detail', $data->id).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.route('admin.user.userPosts', $data->id).'" title="User Post" type="button" id="' . $data->id . '" class="btn btn-primary btn-sm">User Posts</a>&nbsp
                                <a href="'.route('admin.user.userComments', $data->id).'" title="User Post" type="button" id="' . $data->id . '" class="btn btn-primary btn-sm">User Comments</a>&nbsp
                                <a href="'.route('admin.user.userRewards', $data->id).'" title="Rewards" type="button" id="' . $data->id . '" class="btn btn-primary btn-sm">Rewards</a>&nbsp
                                <a target="_blank" href="'.route('userProfile', $data->id).'" title="User Post" type="button" id="' . $data->id . '" class="btn btn-primary btn-sm">Profile</a>';
                    })->rawColumns(['profile_picture', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.user.list');
    }

    final public function show(int $id){
        $content= User::find($id);
        return view('admin.user.view',compact('content'));
    }

    final public function edit(Request $request, $id){
        if ($request->method() == 'POST') {
            $this->validate($request, array(
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
                'password' => 'sometimes',
            ));

            $user = User::find($id);

            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->zipcode = $request->input('zipcode');
            if($request->has('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            if ($user->save()) {
                return redirect()->route('user')->with(['success' => 'Customer Edit Successfully']);
            }
        }else {
            $content=User::findOrFail($id);
            return view('admin.user.add-user', compact('content'));
        }
    }

    final public function destroy($id)
    {
        $content=User::find($id);
        $content->delete();
        echo 1;
    }

    final public function suspend($id)
    {
        $content=User::find($id);
        $content->suspended_on = Carbon::now();
        $content->save();
        echo 1;
    }

    final public function close($id)
    {
        $content=User::find($id);
        $content->closed_on = Carbon::now();
        $content->save();

        //pause subscription
        toggle_user_subscription($content->id, true, false);

        return redirect()->back()->with('success', 'Account Closed!');
        echo 1;

    }

    public function userPosts($id)
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(Post::where('user_id', $id)->orderBy('created_at', 'DESC')->get())
                    ->addIndexColumn()
                    ->editColumn('created_at', function($data){
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('m-d-Y');
                        return $formatedDate;
                    })->addColumn('media', function ($data) {
                        $files = [];
                        foreach ($data->media as $media) {
                            $files[] = [
                                'mime_type' => $media->mime_type,
                                'url' => $media->original_url,
                            ];
                        }
                        return count($files) && isset($files[0]) && isset($files[0]['url']) ? '<img width="40" src="'.$files[0]['url'].'"></img>' : '';
                        return '<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['media', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.user.post-list');
    }

    public function userComments($id)
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(Comment::where('user_id', $id)->orderBy('created_at', 'DESC')->get())
                    ->addIndexColumn()
                    ->editColumn('created_at', function($data){
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('m-d-Y');
                        return $formatedDate;
                    })
                    ->rawColumns(['media', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.user.comment-list');
    }

    public function userRewards($id)
    {
        try {
            if (request()->ajax()) {
                $reward_logs = RewardLog::whereHas('reward', function ($q) use ($id) {
                    return $q->whereHas('user')->whereHas('invited_user')->where('user_id', $id);
                })->orderBy('created_at', 'DESC')->get();
//                dd($reward_logs);

                return datatables()->of($reward_logs)
                    ->addIndexColumn()
                    ->addColumn('on_inviting', function ($data) {
                        return $data->reward->invited_user->username;
                    })
                    ->addColumn('amount', function ($data) {
                        return $data->reward->amount;
                    })
                    ->editColumn('created_at', function($data){
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('m-d-Y');
                        return $formatedDate;
                    })->rawColumns(['media', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.user.reward-list');
    }

    final public function postDestroy($id)
    {
        $content=Post::find($id);
        $content->delete();
        echo 1;
    }

    public function detail($id)
    {
        try {
            $user = User::find($id);

            //referrals
            $referrals = \App\Models\Reward::whereHas('user')->whereHas('invited_user')->where('user_id', $id)->get();

            //payments
            $payments = [];
            $total_payment = 0;
            if (!is_null($user->stripe_checkout_session_id)) {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET_KEY')
                );

                try {
                    $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);
                    $invoices = $stripe->invoices->all([
                        'subscription' => $subscription->id,
                    ]);

                    //main1146's patch
                    if ($user->username == 'main1146') {
                        $invoices = array_merge($invoices, $stripe->invoices->all([
                            'subscription' => 'sub_1NvT7EFNDZX6ZunfSRfICA1a',
                        ]));
                    }

                    foreach ($invoices as $invoice) {
                        if (!is_null($invoice->charge)) {
                            $charge = $stripe->charges->retrieve($invoice->charge);
                            switch ($charge->status) {
                                case "succeeded":
                                    $status = '<span class="badge badge-pill badge-success">Succeeded</span>';
                                    break;
                                case "pending":
                                    $status = '<span class="badge badge-pill badge-warning">Pending</span>';
                                    break;
                                case "failed":
                                    $status = '<span class="badge badge-pill badge-danger">Failed</span>';
                                    break;
                                default:
                                    $status = '';
                                    break;
                            }

                            $payments []= [
                                'amount' => ('$' . $charge->amount / 100) ?? '',
                                'date' => (Carbon::parse($charge->created)->format('M d, Y.')) ?? '',
                                'status' =>  $status,
                            ];

                            $total_payment += ($charge->amount / 100);
                        }
                    }
                } catch (InvalidRequestException $e) {
                    $subscription = null;
                }

            } else {
                $subscription = null;
            }

            //reward logs
            $reward_logs = RewardLog::whereHas('reward', function ($q) use ($id) {
                return $q->whereHas('user')->whereHas('invited_user')->where('user_id', $id);
            })->orderBy('created_at', 'DESC')->get();

            $total_referral_payment = 0;
            foreach ($reward_logs as $reward_log) {
                $total_referral_payment += $reward_log->reward->amount;
            }

            return view('admin.user.detail', compact('user', 'referrals', 'payments', 'subscription', 'reward_logs', 'total_referral_payment', 'total_payment'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
        return view('admin.user.post-list');
    }
}
