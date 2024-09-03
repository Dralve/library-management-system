<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
 * Store a newly created rating in storage.
 * @param  RatingRequest  $request
 * @return JsonResponse
 */
    public function store(RatingRequest $request): JsonResponse
    {
        $user = Auth::user();
        $bookId = $request->book_id;

        $rating = Rating::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $bookId],
            $request->validated()
        );

        return response()->json(new RatingResource($rating), 201);
    }

    /**
     * Display the specified rating along with the average rating of the book.
     * @param  Rating  $rating
     * @return JsonResponse
     */
    public function show(Rating $rating)
    {
        Log::info('Rating object:', $rating->toArray());

        if (!$rating) {
            return response()->json(['error' => 'Rating not found'], 404);
        }

        $rating->load('book');

        $averageRating = Rating::where('book_id', $rating->book_id)->avg('rating');

        Log::info('Average rating:', ['average_rating' => $averageRating]);

        $data = [
            'rating' => new RatingResource($rating),
            'average_rating' => $averageRating,
        ];

        return response()->json($data, 200);
    }

    /**
 * Update the specified rating in storage.
 * @param  RatingRequest  $request
 * @param  Rating  $rating
 * @return JsonResponse
 */
    public function update(RatingRequest $request, Rating $rating): JsonResponse
    {
        if ($rating->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rating->update($request->validated());

        return response()->json(new RatingResource($rating), 200);
    }

    /**
     * Remove the specified rating from storage.
     * @param  Rating  $rating
     * @return JsonResponse
     */
    public function destroy(Rating $rating): JsonResponse
    {
        if ($rating->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rating->delete();
        return response()->json(null, 204);
    }
}
