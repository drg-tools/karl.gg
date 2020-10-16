<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools;

class GearController extends Controller
{
    use SEOTools;

    public function index()
    {
        $this->seo()->setTitle('Deep Rock Galactic Gear');

        return view('gear.index');
    }
}
