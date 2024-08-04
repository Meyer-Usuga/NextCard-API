<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Método que muestra todos los roles
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = Role::all();

        if($roles){
            $data = array(
                'status' => 'success', 
                'code' => 200,
                'data' => $roles
            );
        }
        else{
            $data = array(
                'status' => 'success', 
                'code' => 404,
                'message' => 'No se encontró ningún rol'
            ); 
        }
        return response()->json($data);
    }

    /**
     * Método que registra un nuevo rol
     * @param \App\Http\Requests\RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $role = Role::create($request->all());
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message'=> 'El rol se creó correctamente',
            'data' => $role
        );

        return response()->json($data); 
    }

    /**
     * Método que permite mostrar un rol por id
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $role = Role::find($id);

        if($role){
            $data = array(
                'status' => "success", 
                'code' => 200,
                'data' => $role
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el rol especificado'
            );
        }

        return response()->json($data);
    }
}
