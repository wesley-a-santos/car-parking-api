<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingStartRequest;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Services\ParkingPriceService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Parking
 */
class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ParkingResource::collection(Parking::active()->get());
    }

    /**
     * Display a listing of the resource.
     */
    public function history(): AnonymousResourceCollection
    {
        return ParkingResource::collection(Parking::stopped()->get());
    }

    public function start(ParkingStartRequest $request): ParkingResource|JsonResponse
    {
        if (Parking::active()->where('vehicle_id', $request->validated('vehicle_id'))->exists()) {
            return response()->json([
                'errors' => [
                    'general' => [
                        'Can\'t start parking twice using same vehicle. Please stop currently active parking.'
                    ]
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $parking = new Parking();
        $parking->vehicle_id = $request->validated('vehicle_id');
        $parking->zone_id = $request->validated('zone_id');
        $parking->save();

        $parking->load('vehicle', 'zone');

        return ParkingResource::make($parking);
    }

    public function show(Parking $parking): ParkingResource
    {
        return ParkingResource::make($parking);
    }

    public function stop(Parking $parking): ParkingResource
    {
        $parking->stop_time = now();
        $parking->total_price = ParkingPriceService::calculatePrice(
            zoneId: $parking->zone_id,
            startTime: $parking->start_time
        );
        $parking->save();

        return ParkingResource::make($parking);
    }
}
