<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RestApi
{
    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/'.
     *
     * @param   Request $request
     * @return  bool
     */
    protected function isApiCall(Request $request)
    {
        return ($request->segment(1) == 'api');
    }

}