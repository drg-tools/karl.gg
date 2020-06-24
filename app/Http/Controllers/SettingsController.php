<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function tokens()
    {
        return view('settings.tokens');
    }
}
