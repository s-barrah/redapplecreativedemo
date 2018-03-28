<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Tracks Index page view.
     */
    public function index(){

        return view('tracks.index')
            ->with('pageTitle', 'Tracks')
            ->with('pageID', 'tracks');
            ->with('tracks', Track::orderBy('id', 'asc')->get());

    }

}
