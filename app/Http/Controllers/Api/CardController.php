<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::where('user_id' , userId())->get();
        return response()->json(['cards' => $cards]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCardRequest $request)
    {
        $user = auth()->user();
        $cardStarChars = substr($request->validated('card_number') , 6 ,6);
        $card_number = str_replace($cardStarChars , '******' , $request->validated('card_number'));
        $card = $user->cards()->create([ 'card_number' => $card_number]);
        return response()->json(['card' => $card] , 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response()->noContent(200);
    }
}
