<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCreatedToday = User::where('created_at', 'like', '%' . date('Y-m-d') . '%')->count();

        return response()->json([
            'usersCreatedToday' => $usersCreatedToday,
            'semana' => User::orderBy('id')->take(10)->get(),
            'mes' => User::orderBy('email', 'desc')->take(10)->get()
        ]);
    }
}
