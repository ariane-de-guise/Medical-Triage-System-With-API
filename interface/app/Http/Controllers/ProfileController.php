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
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/profiles/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $user = $response['user'];
        return view('profile.show', compact('user'));
    }
}
