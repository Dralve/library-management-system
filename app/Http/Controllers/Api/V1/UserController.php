<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Requests\V1\UpdateUserRoleRequest;
use App\Http\Requests\V1\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('auth:api', ['only' => ['update']]);

        $this->middleware('admin', ['only' => ['store', 'updateRole', 'destroy']]);
    }

    /**
     * Display a listing of all users.
     *
     * This method is only accessible to users with the 'admin' role.
     * It retrieves and paginates the list of all users.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $users = User::paginate(10);
            return response()->json(['data' => $users], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
 * Update the authenticated user's information.
 *
 * @param  UpdateUserRequest  $request
 * @return JsonResponse
 */
    public function update(UpdateUserRequest $request)
    {
        $authUser = Auth::user();

        try {
            $authUser->update($request->validated());
        } catch (\Exception $e) {
            Log::error('Update user error: ' . $e->getMessage());

            return response()->json(['error' => 'Unable to update user information'], 500);
        }

        return response()->json(new UserResource($authUser), 200);
    }





     /**
     * Display the details of a specific user.
     *
     * This method is only accessible to users with the 'admin' role.
     * It retrieves the details of the specified user.
     *
     * @param  User  $user  The user model instance
     * @return JsonResponse
     */
    public function show(User $user)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return response()->json(['data' => $user], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
     * Update a user's role (admin only).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Only admin can create users.'], 403);
        }

        $user = User::create($request->validated());

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Remove the specified user (admin only).
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
