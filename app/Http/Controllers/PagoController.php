<?php
 
namespace App\Http\Controllers;
 
use App\Models\Pago;
use Illuminate\Http\Request;
 
class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pago::with('inscripcion.postulante')->orderBy('id', 'desc')->get();
        return view('admin.pagos.index', compact('pagos'));
    }
 
}