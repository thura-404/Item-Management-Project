<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 12/07/2023
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.dashboard');
    }
}
