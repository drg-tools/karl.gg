<?php

namespace App\Http\Controllers;

use App\Character;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\View\View;

/**
 * Standalone build calculator. It is unlisted while the community tries it,
 * so it has no navigation entry and asks search engines not to index it.
 */
class CalculatorController extends Controller
{
    public function show(): View
    {
        SEOTools::setTitle('Build Calculator');
        SEOMeta::setRobots('noindex, nofollow');

        return view('calculator', ['calculator' => [
            'characters' => Character::orderBy('name')->get()->map(fn (Character $character): array => [
                'id' => $character->id, 'name' => $character->name,
                'url' => route('builder.catalog', $character),
            ]),
        ]]);
    }
}
