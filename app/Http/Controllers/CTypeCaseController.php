<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Crm\CTypeCase;
class CTypeCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeCase = CTypeCase::all();

        return response()->json($typeCase);
    }
}
