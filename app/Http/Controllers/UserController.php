<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Método que permite mostrar todos los usuarios
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        /** Obtenemos todos los usuarios */
        $users = User::with('entry', 'exit')->get(); 

        if ($users) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $users
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontraron usuarios disponibles'
            );
        }

        return response()->json($data);
    }

    /**
     * Método que permite registrar un usuario
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        /** Validamos datos */
        $exist_document = User::where('document', $request->document)->exists();
        $exist_email = User::where('email', $request->email)->exists();

        if (!$exist_document && !$exist_email) {

            /** Creamos el usuario cifrando su clave */
            $request->merge(['password' => Hash::make($request->password)]);
            $user = User::create($request->all());

            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $user
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 409,
                'message' => 'Usuario o email existente'
            );
        }

        return response()->json($data);
    }

    /**
     * Método que permite mostrar un usuario por id
     * @param string $id
     * @return JsonResponse|mixed
     */
    public function show(string $id)
    {
        /** Obtenemos la data del usuario */
        $user = User::find($id);

        if ($user) {
            $data = array(
                'status' => "success",
                'code' => 200,
                'data' => $user
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el usuario especificado'
            );
        }

        return response()->json($data);
    }

    /**
     * Método que permite actualizar un usuario
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return JsonResponse|mixed
     */
    public function update(Request $request, string $id)
    {
        /** Consultamos el usuario */
        $user = User::find($id);

        if ($user) {

            /** Validamos datos */
            $exist_document = User::where('document', $request->document)
                ->where('id', '!=', $user->id)->exists();
            $exist_email = User::where('email', $request->email)
                ->where('id', '!=', $user->id)->exists();

            if (!$exist_document && !$exist_email) {

                /** Actualizamos */
                $user->document = $request->document;
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->groupId = $request->groupId;
                $user->rol = $request->rol;
                $user->status = $request->status;

                /** Verificamos si se cambio la contraseña*/
                if (!Hash::check($request->password, $user->password)) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'data' => $user
                );

            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 409,
                    'message' => 'Documento o correo existentes'
                );
            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el usuario especificado'
            );
        }

        return response()->json($data);
    }

    /**
     * Método que permite loguear un usuario
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function login(Request $request)
    {
        /** Obtenemos datos a partir del correo */
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                //creación del token
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'data' => $user
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Credenciales incorrectas'
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe ningún usuario con el correo especificado'
            );
        }

        return response()->json($data);
    }

}
