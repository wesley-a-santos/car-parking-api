<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Auth
 */
class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user()->only('name', 'email'));
    }

    public function update(ProfileRequest $request): JsonResponse
    {


        /**
         * @var User $user
         */
        $user = auth()->user();

        $user->name = $request->validated('name');
        $user->email = $request->validated('email');
        $user->save();

        return response()->json($request->validated(), Response::HTTP_ACCEPTED);
    }
}
