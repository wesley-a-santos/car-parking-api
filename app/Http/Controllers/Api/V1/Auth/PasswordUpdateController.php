<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Auth
 */
class PasswordUpdateController extends Controller
{
    public function __invoke(PasswordUpdateRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        $user->password = Hash::make($request->validated('password'));
        $user->save();

        return response()->json([
            'message' => 'Your password has been updated.',
        ], Response::HTTP_ACCEPTED);
    }
}
