<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Rental $rental)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'review_id' => 'nullable|integer',
        ]);

        $userId = auth()->id();

        $existingReview = Review::where('rental_id', $rental->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'] ?? '',
            ]);
        } else {
            Review::create([
                'rental_id' => $rental->id,
                'user_id' => $userId,
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'] ?? '',
            ]);
        }

        return redirect()->route('rentals.index');
    }
}
