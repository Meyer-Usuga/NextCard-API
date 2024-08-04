<?php

namespace App\Http\Controllers;

use App\Models\userEntry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Método para mostrar todas las entradas
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** Obtenemos todas las entradas con usuario incluído */
        $list_entries = userEntry::all(); 
        
        if ($list_entries) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $list_entries
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No hay entradas registradas'
            );
        }

        return response()->json($data);
    }

    /**
     * Método para guardar una entrada
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Método para mostrar una entrada por id
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        //
    }
}
