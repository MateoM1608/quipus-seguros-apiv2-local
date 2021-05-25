<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\GCountry;

class GCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $country = GCountry::all();

        return response()->json($country);
    }
}
