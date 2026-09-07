<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\PersonalInfo;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $cards = Card::where('user_id', $request->user()->id)
            ->with(['card' => function ($query) {
                // Ensure deleted cards are not returned if using soft deletes
                $query->where('is_delete', false);
            }])
            ->latest('id')
            ->get()
            ->filter(fn ($item) => !is_null($item->card))
            ->map(function ($item) {
                // Flatten the response so the frontend receives a clean card object
                $cardData = $item->card->toArray();
                $cardData['pivot_id'] = $item->id;
                $cardData['remarks'] = $item->remarks;
                return $cardData;
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $cards,
        ]);
    }

    public function delete(Request $request , $card_id)
    {

        $deleted = Card::where('user_id', $request->user()->id)->where('card_id', $card_id)->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found or already removed.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'card_id' => 'required|exists:personal_infos,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $userId = $request->user()->id;

        $exists = Card::where('user_id', $userId)
            ->where('card_id', $validatedData['card_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This card already exists in your vault.',
            ], 409); // 409 Conflict is more semantic than 400
        }

        $validatedData['user_id'] = $userId;
        $pivot = Card::create($validatedData);

        // Load the card data for direct frontend state appending
        $cardDetails = PersonalInfo::find($validatedData['card_id'])->toArray();
        $cardDetails['pivot_id'] = $pivot->id;
        $cardDetails['remarks'] = $pivot->remarks;

        return response()->json([
            'success' => true,
            'message' => 'Card added successfully.',
            'data' => $cardDetails,
        ], 201);
    }
}