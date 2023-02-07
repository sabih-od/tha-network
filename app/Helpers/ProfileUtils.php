<?php

namespace App\Helpers;

class ProfileUtils
{
    public static function profileImg($user, $collection)
    {
        $img = null;
        if ($user) {
            $img = $user->getFirstMedia($collection)->original_url ?? null;
        }
        return $img;
    }
}
