<?php

namespace App\Http\Controllers\AdministratorV2;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('administrator-v2.dashboard.index');
    }
}