<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function show(): Response
    {
        return Inertia::render('Public/Home',
        // [
        //     'user' => User::findOrFail($id)
        // ]
      );
    }
}
