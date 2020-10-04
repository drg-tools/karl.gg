<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

class SettingsController extends Controller
{
    public function tokens()
    {
        SEOTools::setTitle('Settings');

        return view('settings.tokens');
    }
}
