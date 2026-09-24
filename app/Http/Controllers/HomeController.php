<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Setting;
use App\Models\TravelRoute;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $jakartaNow = Carbon::now('Asia/Jakarta');

        $featuredRoutes = TravelRoute::query()
            ->with('bus')
            ->where('status', 'available')
            ->where('available_seats', '>', 0)
            ->whereHas('bus', fn ($query) => $query->where('status', 'active'))
            ->where(function ($query) use ($jakartaNow) {
                $query->whereDate('departure_date', '>', $jakartaNow->toDateString())
                    ->orWhere(function ($todayQuery) use ($jakartaNow) {
                        $todayQuery->whereDate('departure_date', $jakartaNow->toDateString())
                            ->where('departure_time', '>=', $jakartaNow->format('H:i:s'));
                    });
            })
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->limit(3)
            ->get();

        $fleet = Bus::query()
            ->where('status', 'active')
            ->orderBy('bus_name')
            ->limit(3)
            ->get();

        $settings = Setting::all()->pluck('value', 'key');

        $originCities = TravelRoute::query()
            ->where('status', 'available')
            ->whereHas('bus', fn ($q) => $q->where('status', 'active'))
            ->distinct()
            ->pluck('origin_city')
            ->filter()
            ->sort()
            ->values();

        if ($originCities->isEmpty()) {
            $originCities = collect(['Jepara', 'Semarang', 'Kudus', 'Pati']);
        }

        $destinationCities = TravelRoute::query()
            ->where('status', 'available')
            ->whereHas('bus', fn ($q) => $q->where('status', 'active'))
            ->distinct()
            ->pluck('destination_city')
            ->filter()
            ->sort()
            ->values();

        if ($destinationCities->isEmpty()) {
            $destinationCities = collect(['Semarang', 'Kudus', 'Jepara', 'Pati', 'Rembang', 'Jakarta']);
        }

        return view('welcome', compact('featuredRoutes', 'fleet', 'settings', 'originCities', 'destinationCities'));
    }
}
