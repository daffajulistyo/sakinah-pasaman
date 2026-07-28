<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $current_role_name = session()->get('current_role_name');
        return view('backend.dashboard', compact('user', 'current_role_name'));
    }
}
