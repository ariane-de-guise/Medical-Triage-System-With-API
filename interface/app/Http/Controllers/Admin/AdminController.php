<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/**
 * Contrôleur d'administration
 * Gère l'accès au tableau de bord administrateur
 */
class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur
     * Vérifie que l'utilisateur a le rôle admin
     *
     * @return RedirectResponse|View
     */
    public function index()
    {
        $user = session('user');

        if (!$user || !in_array('admin', $user['roles'])) {
            return redirect('/login');
        }

        return view('admin.index', compact('user'));
    }
}
