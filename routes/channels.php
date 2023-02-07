<?php

use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return $user->id == $id;
});

Broadcast::channel('App.Models.Channel.{id}', function ($user, $id) {
    return Channel::where('id', (int) $id)->whereHas('users', function($q) use ($user) {
        $q->where('id', $user->id);
    })->exists();
});
