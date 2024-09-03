<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BorrowRecordRequest;
use App\Http\Requests\V1\UpdateBorrowRecordRequest;
use App\Models\BorrowRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BorrowRecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $borrowRecords = BorrowRecord::with('book', 'user')->get();
            return response()->json($borrowRecords);
        }

        $borrowRecords = BorrowRecord::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        return response()->json($borrowRecords);
    }

/**
 * Store a newly created borrow record.
 *
 * @param  \App\Http\Requests\BorrowRecordRequest  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function store(BorrowRecordRequest $request)
{
    $bookId = $request->book_id;
    $userId = Auth::id();

    $existingBorrowRecord = BorrowRecord::where('book_id', $bookId)
        ->whereNull('due_date')
        ->first();

    if ($existingBorrowRecord) {
        return response()->json([
            'error' => 'The book is currently borrowed by another user and cannot be borrowed until it is returned.',
        ], 400);
    }

    $userExistingBorrowRecord = BorrowRecord::where('book_id', $bookId)
        ->where('user_id', $userId)
        ->whereNull('due_date')
        ->first();

    if ($userExistingBorrowRecord) {
        return response()->json([
            'error' => 'You have already borrowed this book and have not returned it yet.',
        ], 400);
    }

    $borrowedAt = Carbon::now();
    $returnedAt = $borrowedAt->copy()->addDays(14);
    $borrowRecord = BorrowRecord::create([
        'book_id' => $bookId,
        'user_id' => $userId,
        'borrowed_at' => $borrowedAt,
        'due_date' => null,
        'returned_at' => $returnedAt,
    ]);

    return response()->json($borrowRecord, 201);
}


    /**
     * Display the specified resource.
     *
     * @param   $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);

        if (Auth::user()->role === 'admin' || $borrowRecord->user_id === Auth::id()) {
            return response()->json($borrowRecord->load('book'));
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

     /**
     * Update the specified borrow record.
     *
     * @param  \App\Http\Requests\V1\UpdateBorrowRecordRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRecordRequest $request, $id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);

        if ($borrowRecord->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $dueDate = $request->due_date;

        if ($dueDate) {
            $dueDate = Carbon::parse($dueDate);
            if ($dueDate->lessThan($borrowRecord->borrowed_at)) {
                return response()->json(['error' => 'Due date must be after the borrowed date'], 422);
            }
        }

        $borrowRecord->update([
            'due_date' => $dueDate,
        ]);

        return response()->json($borrowRecord);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param   $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $borrowRecord->delete();

        return response()->json(['message' => 'Borrow record deleted successfully'], 200);
    }
}
