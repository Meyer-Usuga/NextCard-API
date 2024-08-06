<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
Use App\Models\Group; 

class GroupsController extends Controller
{
    /**
     * Método para mostrar todos los grupos
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $groups = Group::all();

        if($groups){
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $groups
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404, 
                'message' => 'No se encontraron grupos'
            );
        }

        return response()->json($data); 
    }

    /**
     * Método para registrar un grupo
     * @param \App\Http\Requests\GroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupRequest $request): JsonResponse
    {
        $group = Group::create($request->all()); 
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message'=> 'El grupo se creó correctamente',
            'data' => $group
        );

        return response()->json($data); 
    }

    /**
     * Método para mostrar un grupo por ID
     * @param mixed $id
     * @return JsonResponse|mixed
     */
    public function show($id)
    {
        $group = Group::find($id); 

        if($group){
            $data = array(
                'status' => "success", 
                'code' => 200,
                'data' => $group
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el grupo especificado'
            );
        }

        return response()->json($data);
    }

    /**
     *  Método para actualizar un grupo
     * @param \App\Http\Requests\GroupRequest $request
     * @param string $id
     * @return JsonResponse|mixed
     */
    public function update(GroupRequest $request, string $id)
    {
        $group = Group::find($id); 

        if($group){
            $group->code = $request->code; 
            $group->name = $request->name; 
            $group->period = $request->period; 
            $group->students = $request->students; 
            $group->teacherId = $request->teacherId; 
            $group->status = $request->status; 
            $group->save();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Grupo actualizado con éxito',
                'data' => $group
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404, 
                'message' => 'No se encontró el grupo'
            );
        }

        return response()->json($data); 
    }

        /**
     * Método para obtener estudiantes por grupo
     * @param string $groupId
     * @return JsonResponse|mixed
     */
    public function getStudents(string $groupId){

        $ROL_STUDENT = 3; 

        /** Obtenemos el grupo */
        $group = Group::find($groupId);
        /** Médiante la relación 1 a muchos obtenemos sus usuarios con rol */
        $students = $group->user()->where('rol', $ROL_STUDENT)->get();

        if(!$students->isEmpty()){
            $data = array(
                'status' => "success",
                'code' => 200,
                'data' => $students
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontraron estudiantes'
            );
        }

        return response()->json($data);

    }
}
