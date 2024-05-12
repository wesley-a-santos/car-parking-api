<?php

namespace App\Services;

use App\Models\Zone;
use Carbon\Carbon;

class ParkingPriceService
{
    public static function calculatePrice(int $zoneId, string $startTime, string $stopTime = null): int
    {
        $start = new Carbon($startTime);
        $stop = (!is_null($stopTime)) ? new Carbon($stopTime) : now();

        $totalTimeByMinutes = $start->diffInMinutes($stop);

        $priceByMinutes = Zone::find($zoneId)->price_per_hour / 60;

        return round($totalTimeByMinutes * $priceByMinutes);
    }
}
