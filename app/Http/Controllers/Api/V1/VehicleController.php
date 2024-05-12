<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Parking;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Vehicles
 */
class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return VehicleResource::collection(Vehicle::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleRequest $request): VehicleResource
    {
        $vehicle = new Vehicle();
        $vehicle->plate_number = $request->validated('plate_number');
        $vehicle->description = $request->validated('description');
        $vehicle->save();

        return VehicleResource::make($vehicle);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): VehicleResource
    {
        return VehicleResource::make($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->plate_number = $request->validated('plate_number');
        $vehicle->description = $request->validated('description');
        $vehicle->save();

        return response()->json(VehicleResource::make($vehicle), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Vehicle $vehicle): Response
    {
        if ($vehicle->parkings()->active()->exists()) {
            return response()->json([
                'errors' => [
                    'general' => [
                        'Can\'t delete a vehicle with active parking. Please stop currently active parking.'
                    ]
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $vehicle->delete();

        return response()->noContent();
    }
}
