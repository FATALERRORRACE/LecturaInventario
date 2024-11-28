<?php

namespace App\Http\Controllers;
use App\Models\Menu;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view(
            'index.main',
            
        );
    }

}
