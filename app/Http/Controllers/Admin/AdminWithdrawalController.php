<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index (Request $request)
    {
        $admin_withdrawals = AdminWithdrawal::query();

        //when year provided
        $year_check = (boolean)($request->has('year') && !is_null($request->year));
        $admin_withdrawals = $admin_withdrawals->when($year_check, function ($q) use ($request) {
            return $q->where( DB::raw('YEAR(date)'), '=', $request->get('year'));
        })->get();

        return $admin_withdrawals;
    }

    public function create (Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'date' => 'required',
        ]);

        AdminWithdrawal::create($request->all());

        return redirect()->route('dashboard');
    }
}
