<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index() {
        $user = session('user');
        return view('dashboard', compact('user'));
    }
}
