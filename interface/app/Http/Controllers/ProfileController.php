<?php

namespace App\Http\Controllers;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function show()
    {
        $userId = session('user')['id'] ?? null;

        $user = Http::get("http://host.docker.internal:81/api/user/$userId")['user'];

        return view('profile.show', compact('user'));
    }
}
