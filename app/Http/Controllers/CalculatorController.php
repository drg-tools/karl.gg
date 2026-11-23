<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

/**
 * Standalone build calculator, linked from the Loadouts menu while it is in
 * beta.
 */
class CalculatorController extends Controller
{
    public function show(): View
    {
        SEOTools::setTitle('Build Calculator');
        SEOTools::setDescription('Try Deep Rock Galactic upgrade and overclock combinations and compare their stats before you save a loadout.');

        return view('calculator', ['calculator' => [
            'characters' => Character::orderBy('name')->get()->map(fn (Character $character): array => [
                'id' => $character->id, 'name' => $character->name,
                'url' => route('builder.catalog', $character),
            ]),
            'builderUrl' => route('builder.create'),
        ]]);
    }
}
