<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        try {

            if ($request->method() == 'POST') {
                foreach ($request->goal_ids as $key => $goal_id) {
                    Goal::findOrFail($goal_id)->update([
                        'target' => $request->goal_targets[$key]
                    ]);
                }

                return redirect()->back()->with('success', 'Goals Updated Successfully');
            } else {
                $goals = Goal::all();

                return view('admin.goal.edit', compact('goals'));
            }
        } catch (\Exception $ex) {
            return redirect('admin/dashboard')->with('error', $ex->getMessage());

        }
    }
}
