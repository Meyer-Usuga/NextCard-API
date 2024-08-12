<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Echo_;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CardController extends Controller
{
    /**
     * Método para mostrar todos los carnets
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $cards = Card::all();

        if ($cards) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $cards
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontraron carnets disponibles'
            );
        }

        return response()->json($data);
    }

    /**
     * Método para crear un carnet
     * @param \App\Http\Requests\CardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        /** Decodificamos los datos del formdata*/
        $image_card = $request->file('image');
        $request = json_decode($request->input('card', true));

        /** Garantizamos que el usuario asociado exista y no tenga carnet */
        $exists_user = User::where('document', $request->user)->exists();
        $belongsToUser = Card::where('user', $request->user)->exists();

        if ($exists_user) {

            if (!$belongsToUser) {

                $card = new Card;
                $card->user = $request->user;
                $card->status = $request->status;
                $card->issueDate = $request->issueDate;
                $card->expiryDate = $request->expiryDate;

                /** Guardamos la imagen del estdudiante */
                $imageName = 'user' . $card->user . '.' . $image_card->extension();
                Storage::disk('public')->putFileAs('images/user', $image_card, $imageName);

                /** Generamos QR y guardamos */
                $qrName = 'qr' . $card->user . '.png';
                $qrCode = QrCode::format('png')->size(300)->generate($card->user);
                Storage::disk('public')->put('images/qr/' . $qrName, $qrCode);

                $fileRoute = 'storage/images/';

                /** Guardamos datos */
                $card->urlQr = $fileRoute . 'qr/' . $qrName;
                $card->urlImage = $fileRoute . 'user/' . $imageName;
                $card->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El carnet se creó correctamente',
                    'data' => $card,
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 409,
                    'message' => 'El usuario ya tiene un carnet asociado',
                );
            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no existe',
            );
        }

        return response()->json($data);
    }

    /**
     * Método para mostrar un carnet por id
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $card = Card::find($id);

        if ($card) {
            $data = array(
                'status' => "success",
                'code' => 200,
                'data' => $card
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el carnet especificado'
            );
        }

        return response()->json($data);
    }

    /**
     * Método para obtener un carnet por usuario
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCardByUser(string $user_document): JsonResponse
    {
        $card = Card::where('user', $user_document)->first();

        if ($card) {
            $data = array(
                'status' => "success",
                'code' => 200,
                'data' => $card
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el carnet especificado'
            );
        }

        return response()->json($data);
    }


    /**
     * Método para actualizar un carnet
     * @param \App\Http\Requests\CardRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        /** Obtenemos datos formdata */
        $image_card = $request->file('image');
        $request = json_decode($request->input('card', true));

        /** Obtenemos el carnet */
        $card = Card::find($id);

        if ($card) {

            if ($card->user != $request->user) {

                /** Validamos datos */
                $belongsToUser = User::where('user', $request->user)
                    ->where('id', '!=', $card->id)->exists();

                if (!$belongsToUser) {

                    /** Path constante para laravel storage */
                    $fileRoute = 'storage/images/';

                    /** Generamos nuevo QR */
                    $qrName = 'qr' . $card->user . '.png';
                    $qrUrl = $fileRoute . 'qr/' . $qrName;
                    $qrCode = QrCode::format('png')->size(300)->generate($request->user);

                    /** Eliminamos el QR actual y guardamos el nuevo */
                    Storage::disk('public')->delete($qrUrl);
                    Storage::disk('public')->put('images/qr/' . $qrName, $qrCode);

                    /** Actualizamos la imagen */
                    $imageName = 'user' . $request->user . '.' . $image_card->extension();
                    $imageUrl = $fileRoute . 'user/' . $imageName;

                    /** Eliminamos la imagen actual y guardamos la nueva */
                    Storage::disk('public')->delete($imageUrl);
                    Storage::disk('public')->putFileAs('images/user', $image_card, $imageName);

                    /** Guardamos todos los datos */
                    $card->urlQr = $qrUrl;
                    $card->imageUrl = $imageUrl;
                    $card->user = $request->user;

                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Se actualizó el carnet y el codigo QR',
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 409,
                        'message' => 'El usuario ingresado ya posee un carnet'
                    );
                }
            } else {
                $card->expiryDate = $request->expiryDate;
                $card->status = $request->status;

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se actualizó pero no se genero nuevo qr',
                );
            }
            $card->save();
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el carnet'
            );
        }

        return response()->json($data);
    }

    public function uploadImage(Request $request, string $id): JsonResponse
    {
        $card = Card::find($id);

        if ($card) {
            if ($request->hasFile('image')) {

                $image_card = $request->file('image');
                $imageName = 'user' . $card->user . '.' . $image_card->extension();
                Storage::disk('public')->putFileAs('images/user', $image_card, $imageName);

                $qrName = 'qr' . $card->user . '.png';
                $qrCode = QrCode::format('png')->size(300)->generate($card->user);
                Storage::disk('public')->put('images/qr/' . $qrName, $qrCode);

                $fileRoute = 'storage/images/';

                /** Guardamos datos */
                $card->urlQr = $fileRoute . 'qr/' . $qrName;
                $card->urlImage = $fileRoute . 'user/' . $imageName;

                $card->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'La imagen se cargó con éxito'
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se cargó ninguna imagen'
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No se encontró el carnet'
            );
        }

        return response()->json($data);
    }
}
