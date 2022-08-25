<?php

namespace App\Http\Controllers;

use App\Helpers\ProfileUtils;
use App\Models\Channel;
use App\Models\Group;
use App\Models\User;
use App\Traits\GroupData;
use App\Traits\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
{
    use UserData, GroupData;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return Inertia::render('Groups', [
            'user' => $user->only('id', 'name', 'email', 'created_at') ?? null,
            'profile_cover' => ProfileUtils::profileImg($user, 'profile_cover'),
            'profile_image' => ProfileUtils::profileImg($user, 'profile_image'),
            "users" => Inertia::lazy(function () use ($request) {
                return $this->getFollowsData($request);
            }),
            'groups' => Inertia::lazy(function () use ($request) {
                return $this->getGroupChannelsData($request);
            }),
            'members' => Inertia::lazy(function () use ($request) {
                return $this->getGroupMembersData($request);
            })
        ]);
    }

    public function userGroups(Request $request, $user_id)
    {
        try {
            $user = User::find($user_id);
            if (is_null($user))
                return redirect()->route('home')->with('error', "Invalid user id!");

            $auth = Auth::user();
            $is_blocked_by_user = $auth->isBlockedBy($user);

            if ($is_blocked_by_user) {
                return redirect(route('home'))->with('error', "You are blocked by user!");
            }

            return Inertia::render('Groups', [
                'user' => $user->only('id', 'name', 'email', 'created_at') ?? null,
                'profile_cover' => ProfileUtils::profileImg($user, 'profile_cover'),
                'profile_image' => ProfileUtils::profileImg($user, 'profile_image'),
                "users" => Inertia::lazy(function () use ($user, $request) {
                    return $this->getFollowsData($request, $user->id);
                }),
                'groups' => Inertia::lazy(function () use ($user, $request) {
                    return $this->getGroupChannelsData($request, $user);
                }),
                'members' => Inertia::lazy(function () use ($user, $request) {
                    return $this->getGroupMembersData($request, $user->id);
                })
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \never
     */
    public function create()
    {
        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('groups')->whereNull('deleted_at')
            ],
            'description' => [
                'required', 'max:1000'
            ],
            'members' => [
                'required', 'array'
            ],
            'members.*.id' => [
                'required',
                Rule::exists('users')->whereNull('deleted_at')
            ],
            'img' => [
                'required', 'image', "max:102400"
            ]
        ]);

        try {
            $auth = Auth::user();

            $members = collect($request->input('members', []))->pluck('id');
            $members->prepend($auth->id);

            DB::beginTransaction();

            $channel = new Channel;
            $channel->creator_id = $auth->id;
            $channel->chat_type = 'group';
            $channel->users()->attach($members->toArray());
            $channel->save();

            $group = $channel->group()->create([
                'creator_id' => $auth->id,
                'name' => $request->name,
                'description' => $request->description,
                'moderators' => []
            ]);

            $group
                ->addMediaFromRequest('img')
                ->toMediaCollection('group_media');

            DB::commit();

            return redirect()->route('groups.index')->with('success', 'Group created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('groups.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'id' => $id
            ]
        );

        $validation = Validator::make($data_validation, [
            'id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value) return;

                    $is_group = Group::where('id', $value)
                        ->where(function ($q) {
                            $q->where('creator_id', Auth::id())
                                ->orWhereHas('moderatorsUser', function ($q2) {
                                    $q2->where('id', Auth::id());
                                });
                        })
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
            'name' => [
                'required',
                Rule::unique('groups')->whereNull('deleted_at')->ignore($id)
            ],
            'description' => [
                'required', 'max:1000'
            ],
            'members' => [
                'required', 'array'
            ],
            'members.*.id' => [
                'required',
                Rule::exists('users')->whereNull('deleted_at')
            ],
            'img' => [
                'nullable', 'image', "max:102400"
            ]
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::findOrFail($id);

            $creator_id = $group->creator_id;
            $auth_id = Auth::id();

            $members = collect($request->input('members', []))->pluck('id');

            if (!in_array($auth_id, $members->all()))
                $members->prepend($auth_id);

            if (!in_array($creator_id, $members->all()))
                $members->prepend($creator_id);

            DB::beginTransaction();

            $group->channel->users()->sync($members->toArray())->save();

            $group->name = $request->name;
            $group->description = $request->description;

            $group->save();

            if ($request->has('img') && !empty($request->img)) {
                $group->clearMediaCollection('group_media');
                $group
                    ->addMediaFromRequest('img')
                    ->toMediaCollection('group_media');
            }


            DB::commit();

            return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('groups.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $validation = Validator::make([
            'id' => $id
        ], [
            'id' => [
                'required',
                Rule::exists('groups')
                    ->whereNull('deleted_at')
                    ->where('creator_id', Auth::id())
            ]
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::findOrFail($id);

            DB::beginTransaction();

            $group->channel()->delete();
            $group->delete();

            DB::commit();

            return redirect()->route('groups.index')->with('success', 'Group deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('groups.index')->with('error', $e->getMessage());
        }
    }

    public function makeGroupModerator(Request $request, $group_id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'group_id' => $group_id
            ]
        );

        $validation = Validator::make($data_validation, [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
            ],
            'group_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value || !$request->user_id) return;

                    $is_group = Group::where('id', $value)
                        ->whereDoesntHave('moderatorsUser', function ($q) use ($request) {
                            $q->where('id', $request->user_id);
                        })
                        ->where(function ($q) {
                            $q->where('creator_id', Auth::id())
                                ->orWhereHas('moderatorsUser', function ($q2) {
                                    $q2->where('id', Auth::id());
                                });
                        })
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::where('id', $group_id)->first();

            $group->moderatorsUser()->attach([$request->user_id])->save();

            return redirect()->route('chatIndex')->with('success', 'Moderator added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function reassignGroupModerator(Request $request, $group_id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'group_id' => $group_id
            ]
        );

        $validation = Validator::make($data_validation, [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
            ],
            'group_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value || !$request->user_id) return;

                    $is_group = Group::where('id', $value)
                        ->whereHas('moderatorsUser', function ($q) use ($request) {
                            $q->where('id', $request->user_id);
                        })
                        ->where(function ($q) {
                            $q->where('creator_id', Auth::id())
                                ->orWhereHas('moderatorsUser', function ($q2) {
                                    $q2->where('id', Auth::id());
                                });
                        })
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::where('id', $group_id)->first();

            $group->moderatorsUser()->detach([$request->user_id])->save();

            return redirect()->route('chatIndex')->with('success', 'Moderator reassigned successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function addGroupMember(Request $request, $group_id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'group_id' => $group_id
            ]
        );

        $validation = Validator::make($data_validation, [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
            ],
            'group_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value || !$request->user_id) return;

                    if ($request->user_id == Auth::id()) {
                        $fail("Invalid user add request!");
                        return;
                    }

                    $is_group = Group::where('id', $value)
                        ->whereHas('channel', function ($q) use ($request) {
                            $q->whereDoesntHave('users', function ($q2) use ($request) {
                                $q2->where('id', $request->user_id);
                            });
                        })
                        ->where(function ($q) {
                            $q->where('creator_id', Auth::id())
                                ->orWhereHas('moderatorsUser', function ($q2) {
                                    $q2->where('id', Auth::id());
                                });
                        })
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::where('id', $group_id)->first();

            $group->channel->users()->attach([$request->user_id])->save();

            return redirect()->route('chatIndex')->with('success', 'Member added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function removeGroupMember(Request $request, $group_id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'group_id' => $group_id
            ]
        );

        $validation = Validator::make($data_validation, [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
            ],
            'group_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value || !$request->user_id) return;

                    if ($request->user_id == Auth::id()) {
                        $fail("Invalid user remove request!");
                        return;
                    }

                    $is_group = Group::where('id', $value)
                        ->whereHas('channel', function ($q) use ($request) {
                            $q->whereHas('users', function ($q2) use ($request) {
                                $q2->where('id', $request->user_id);
                            });
                        })
                        ->where(function ($q) {
                            $q->where('creator_id', Auth::id())
                                ->orWhereHas('moderatorsUser', function ($q2) {
                                    $q2->where('id', Auth::id());
                                });
                        })
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::where('id', $group_id)->first();

            $group->channel->users()->detach([$request->user_id])->save();

            $group->moderatorsUser()->detach([$request->user_id])->save();

            return redirect()->route('chatIndex')->with('success', 'Member removed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function leaveGroup(Request $request, $group_id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'group_id' => $group_id
            ]
        );

        $validation = Validator::make($data_validation, [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
            ],
            'group_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value || !$request->user_id) return;

                    $is_group = Group::where('id', $value)
                        ->whereHas('channel', function ($q) use ($request) {
                            $q->whereHas('users', function ($q2) use ($request) {
                                $q2->where('id', $request->user_id);
                            });
                        })
                        ->where('creator_id', '<>', $request->user_id)
                        ->exists();

                    if (!$is_group) {
                        $fail("Invalid request!");
                    }
                },
            ],
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            $group = Group::where('id', $group_id)->first();

            $group->channel->users()->detach([$request->user_id])->save();

            $group->moderatorsUser()->detach([$request->user_id])->save();

            return redirect()->route('chatIndex')->with('success', 'User leave the group successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }
}
