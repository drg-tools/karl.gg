<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function tokens()
    {
        return view('settings.tokens');
    }
}
