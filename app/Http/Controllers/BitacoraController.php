<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Bitacora;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bitacora = Bitacora::orderBy('hora', 'desc')->get();
        return view('admin.bitacora.index', compact('bitacora'));
    }

}
