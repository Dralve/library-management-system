<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the categories along with their books.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $categories = Category::with('books')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  CategoryRequest  $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201);
    }

    /**
     * Display the specified category along with its books.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function show(Category $category)
    {
        $category->load('books'); 
        return response()->json($category);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  CategoryRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
