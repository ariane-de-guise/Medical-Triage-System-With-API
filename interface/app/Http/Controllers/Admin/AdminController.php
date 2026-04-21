<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function index() {
        $user = session('user');

        if (!$user || !in_array('admin', $user['roles'])) {
            return redirect('/login');
        }

        return view('admin.index', compact('user'));
    }
}
