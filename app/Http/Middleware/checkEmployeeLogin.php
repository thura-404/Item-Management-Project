<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class checkEmployeeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $checkLogin = Validator::make($request->all(), [
            'txtId' => 'required',
            'txtPassword' => 'required',
        ]);

        if  ($checkLogin->fails())
        {
            return redirect()->back()->withErrors($checkLogin)->withInput();
        }

        
        return $next($request);
    }
}
