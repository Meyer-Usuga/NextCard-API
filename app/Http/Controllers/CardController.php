<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Método para mostrar todos los carnets
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $cards = Card::all();

        if($cards){
            $data = array(
                'status' => 'success',
                'code' => 200, 
                'data' => $cards
            );
        }
        else{
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
    public function store(CardRequest $request): JsonResponse
    {
        $card = Card::create($request->all()); 
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message'=> 'El carnet se creó correctamente',
            'data' => $card
        );

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

       if($card){
        $data = array(
            'status' => "success", 
            'code' => 200,
            'data' => $card
        );
        }
        else{
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
    public function update(CardRequest $request, string $id): JsonResponse
    {
        $card = Card::find($id);

        if($card){

            $card->urlQr = $request->urlQr; 
            $card->urlImage = $request->urlImage; 
            $card->expiryDate = $request->expiryDate;
            $card->status = $request->status; 
            $card->save();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Carnet actualizado con éxito',
                'data' => $card
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code' => 404, 
                'message' => 'No se encontró el carnet'
            );
        }

        return response()->json($data);
    }
}
