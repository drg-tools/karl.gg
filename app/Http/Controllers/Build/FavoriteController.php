<?php

namespace App\Http\Controllers\Build;

use App\Build;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * @param Request $request
     * @param Build $build
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Build $build)
    {
        $build->toggleFavorite();

        return redirect()->back();
    }
}
