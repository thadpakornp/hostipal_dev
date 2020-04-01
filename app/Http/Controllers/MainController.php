<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['user.status'])->except('index');
    }

    public function index()
    {
        return redirect()->route('backend.charts.index');
    }
}
