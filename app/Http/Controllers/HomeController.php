<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($user = auth()->user()) {
            // if($user->is_admin) {
            //     return redirect()->route('admin.meets.index');
            // }

            // return redirect()->route('meets.index');
            return view('home');
        }

        return redirect('/login');
    }
}
