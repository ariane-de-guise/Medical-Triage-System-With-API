<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class WelcomeController
{
    public function index() {
        try {
            $ressources = collect(Http::get('http://host.docker.internal:81/api/ressources')->json());
        } catch (ConnectionException) {
            $ressources = [];
        }

        return view('welcome', compact('ressources'));
    }
}
