<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Método que permite mostrar todos los usuarios
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        /** Obtenemos la colección de usuarios */
        $users = User::all();

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
        $user_card = $user->card->urlImage;
        
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

                /** Actualizamos QR si posee */
                if ($user->document != $request->document) {
                    if(Card::where('user', $user->document)->exists()){
                        $this->updateFiles($request, $user->document); 
                    }
                }
                /** Guardamos datos*/
                try{
                    $user->document = $request->document;
                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->email = $request->email;
                    $user->groupId = $request->groupId;
                    $user->rol = $request->rol;
                    $user->status = $request->status;

                    /** Verificamos si se cambio la contraseña */
                    if (!empty($request->password)) {

                        if (!Hash::check($request->password, $user->password)) {
                            $user->password = Hash::make($request->password);
                        }
                    }
                    $user->save();

                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $user
                    );
                }
                catch(\Exception $e){
                    Log::error('Error al guardar el usuario' . $e->getMessage());
                }

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
     * Método para actualizar codigo QR e imágen del carnet de un usuario
     * @param mixed $request_data
     * @param mixed $currentDocument
     * @return void
     */
    public function updateFiles($request_data, $currentDocument){
        
        /** Generamos un nuevo QR para el carnet asociado */
        $card_user = Card::where('user', $currentDocument)->first();

        if (!is_null($card_user)) {

            /** Eliminamos el QR actual */
            $qrUrl = explode('storage/', $card_user->urlQr);
            Storage::disk('public')->delete($qrUrl[1]);

            /** Generamos nuevo QR */
            $fileRoute = 'storage/images/';
            $qrName = 'qr' . $request_data->document . '.png';
            $qrUrl = $fileRoute . 'qr/' . $qrName;
            $qrCode = QrCode::format('png')->size(300)->generate($request_data->document);
            Storage::disk('public')->put('images/qr/' . $qrName, $qrCode);

            /** Guardamos datos */
            try{
                $card_user->urlQr = $qrUrl;
                $card_user->save();
            }
            catch(\Exception $e){
                Log::error('Error al guardar el carnet' . $e->getMessage());
            }
        }
    }

    /**
     * Método para inactivar/activar un carnet y usuario
     * @param string $userId
     * @return JsonResponse|mixed
     */
    public function updateStatus(string $userId)
    {
        /** Consultamos el usuario */
        $user = User::find($userId);

        if ($user) {

            /** Cambiamos el estado a ambos */
            if ($user->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
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
