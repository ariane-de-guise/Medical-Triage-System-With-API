<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index() {
        $userId = session('user')['id'] ?? null;

        if (!$userId) {
            return redirect('/login');
        }

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/profiles/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $user = $response['user'];
        $roles = $response['roles'];
        return view('dashboard', compact('user', 'roles'));
    }
}
