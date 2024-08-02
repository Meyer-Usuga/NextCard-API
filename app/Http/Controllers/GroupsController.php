<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
Use App\Models\Group; 

class GroupsController extends Controller
{
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
}
