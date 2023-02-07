<?php

namespace App\Helpers;

class WebResponses
{
    public static function success($message = 'Request submitted successfully!', $data = null, $route = null)
    {
        $route = $route ?? url()->previous(true);
        $redirect = redirect($route);

        if (!empty($message))
            $redirect->with('success', $message);

        if (!empty($data))
            $redirect->with('v_data', $data);

        return $redirect;
    }

    public static function exception($message = 'Server Exception!', $route = null)
    {
        $route = $route ?? url()->previous(true);
        return redirect($route)->with('error', $message);
    }
}
