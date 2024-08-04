<?php

namespace App\Http\Controllers;

use App\Models\userExit;
use Illuminate\Http\Request;

class ExitController extends Controller
{
    /**
     * Método para mostrar todas las salidas
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** Obtenemos todas las salidas con usuario incluído */
        $list_exists = userExit::all(); 
        
        if ($list_exists) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $list_exists
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No hay salidas registradas'
            );
        }

        return response()->json($data);
    }

    /**
     * Método para guardar una salida
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Método para mostrar una salida por id
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        //
    }

}
