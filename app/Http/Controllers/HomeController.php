<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function htmltopdfview()
    {
        view()->share('products',[
            [
                'id' => 1,
                'name' => 'Sample 1'
            ]
        ]);

        $pdf = PDF::loadView('htmltopdfview');
        return $pdf->download('htmltopdfview');
    }
}
