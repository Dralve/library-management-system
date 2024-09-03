<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BookRequest;
use App\Http\Requests\V1\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'filter']]);
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy',]]);
    }

    /**
     * Display a listing of all books for users and admins.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                
                $books = Book::with('borrowRecords')->paginate(10);
            } else {
                
                $books = Book::paginate(10);
            }
            
            return BookResource::collection($books)->response();
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Store a newly created resource in storage.
     * @param  BookRequest  $request
     * @return BookResource
     */
    public function store(BookRequest $request): BookResource
    {
        $book = Book::create($request->validated());
        return new BookResource($book);
    }

  /**
 * Display the specified resource.
 * @param  Book  $book
 * @return JsonResponse
 */
    public function show(Book $book): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                $book->load('borrowRecords');
            }

            $book->load('ratings');

            $averageRating = $book->ratings()->avg('rating');

            $data = [
                'book' => new BookResource($book),
                'average_rating' => $averageRating,
            ];

            return response()->json($data, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateBookRequest  $request
     * @param  Book  $book
     * @return BookResource
     */
    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $book->update($request->validated());
        return new BookResource($book);
    }

    /**
     * Remove the specified book from storage.
     * @param  Book  $book
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();
        return response()->json(null, 204);
    }

    /**
     * Filter books based on title, author, category, and availability.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function filter(Request $request): JsonResponse
    {
        $author = $request->input('author');
        $categoryName = $request->input('category');
        $available = $request->input('available');

        $query = Book::query();

        if ($author) {
            $query->where('author', 'like', "%{$author}%");
        }

        if ($categoryName) {
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', 'like', "%{$categoryName}%");
            });
        }

        if ($available === 'true') {
            $query->whereDoesntHave('borrowRecords', function ($q) {
                $q->whereNull('due_date');
            });
        } else if ($available === 'false') {
            $query->whereHas('borrowRecords', function ($q) {
                $q->whereNull('due_date');
            });
        }

        $books = $query->get();

        return BookResource::collection($books)->response();
    }

}
