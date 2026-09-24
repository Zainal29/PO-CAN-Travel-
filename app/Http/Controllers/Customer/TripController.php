<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchTripRequest;
use App\Models\Bus;
use App\Models\TravelRoute;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
   public function index(SearchTripRequest $request): View|RedirectResponse
    {
        $routes = collect();
        $matchingSchedules = 0;
        $jakartaNow = Carbon::now('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | Available Routes
        |--------------------------------------------------------------------------
        */
        $availableRoutes = TravelRoute::query()
            ->with('bus')
            ->where('status', 'available')
            ->where('available_seats', '>', 0)
            ->whereHas('bus', function ($query) {
                $query->where('status', 'active');
            })
            ->where(function ($query) use ($jakartaNow) {
                $query
                    ->whereDate(
                        'departure_date',
                        '>',
                        $jakartaNow->toDateString()
                    )
                    ->orWhere(function ($todayQuery) use ($jakartaNow) {
                        $todayQuery
                            ->whereDate(
                                'departure_date',
                                $jakartaNow->toDateString()
                            )
                            ->where(
                                'departure_time',
                                '>=',
                                $jakartaNow->format('H:i:s')
                            );
                    });
            })
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Available Buses
        |--------------------------------------------------------------------------
        */
        $availableBuses = Bus::query()
            ->where('status', 'active')
            ->whereHas('routes', function ($query) use ($jakartaNow) {
                $query
                    ->where('status', 'available')
                    ->where('available_seats', '>', 0)
                    ->where(function ($dateQuery) use ($jakartaNow) {
                        $dateQuery
                            ->whereDate(
                                'departure_date',
                                '>',
                                $jakartaNow->toDateString()
                            )
                            ->orWhere(function ($todayQuery) use ($jakartaNow) {
                                $todayQuery
                                    ->whereDate(
                                        'departure_date',
                                        $jakartaNow->toDateString()
                                    )
                                    ->where(
                                        'departure_time',
                                        '>=',
                                        $jakartaNow->format('H:i:s')
                                    );
                            });
                    });
            })
            ->withCount([
                'routes as available_routes_count' => function ($query) use ($jakartaNow) {
                    $query
                        ->where('status', 'available')
                        ->where('available_seats', '>', 0)
                        ->where(function ($dateQuery) use ($jakartaNow) {
                            $dateQuery
                                ->whereDate(
                                    'departure_date',
                                    '>',
                                    $jakartaNow->toDateString()
                                )
                                ->orWhere(function ($todayQuery) use ($jakartaNow) {
                                    $todayQuery
                                        ->whereDate(
                                            'departure_date',
                                            $jakartaNow->toDateString()
                                        )
                                        ->where(
                                            'departure_time',
                                            '>=',
                                            $jakartaNow->format('H:i:s')
                                        );
                                });
                        });
                },
            ])
            ->orderBy('bus_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Search State
        |--------------------------------------------------------------------------
        */
        $searchFields = [
            'origin_city',
            'destination_city',
            'departure_date',
            'passengers',
            'bus_type',
            'departure_period',
            'max_price',
        ];

        $hasAnySearch = $request->anyFilled($searchFields);

        $hasCompleteSearch =
            $request->filled('origin_city') &&
            $request->filled('destination_city') &&
            $request->filled('departure_date');

        if ($hasCompleteSearch) {
            $departureDate = $request->date('departure_date');

            $originCity = mb_strtolower(
                trim($request->string('origin_city')->toString())
            );

            $destinationCity = mb_strtolower(
                trim($request->string('destination_city')->toString())
            );

            /*
            |--------------------------------------------------------------------------
            | Base Search
            |--------------------------------------------------------------------------
            | Ini menentukan apakah memang ada jadwal untuk rute + tanggal
            | yang dicari.
            |
            | Filter opsional TIDAK memengaruhi matchingSchedules.
            |--------------------------------------------------------------------------
            */
            $baseSearch = TravelRoute::query()
                ->where('status', 'available')
                ->whereHas('bus', function ($query) {
                    $query->where('status', 'active');
                })
                ->whereDate(
                    'departure_date',
                    $departureDate->toDateString()
                )
                ->whereRaw(
                    'LOWER(TRIM(origin_city)) = ?',
                    [$originCity]
                )
                ->whereRaw(
                    'LOWER(TRIM(destination_city)) = ?',
                    [$destinationCity]
                )
                ->where('available_seats', '>', 0)
                ->when(
                    $departureDate->isSameDay($jakartaNow),
                    function ($query) use ($jakartaNow) {
                        $query->where(
                            'departure_time',
                            '>=',
                            $jakartaNow->format('H:i:s')
                        );
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | Jumlah jadwal untuk rute dasar
            |--------------------------------------------------------------------------
            | Contoh:
            |
            | Jepara → Semarang
            | 3 jadwal tersedia
            |
            | Jika filter harga kemudian membuat hasil menjadi 0,
            | matchingSchedules tetap 3.
            |--------------------------------------------------------------------------
            */
            $matchingSchedules = (clone $baseSearch)->count();

            /*
            |--------------------------------------------------------------------------
            | Search Results
            |--------------------------------------------------------------------------
            | Filter opsional hanya memengaruhi hasil yang ditampilkan.
            |--------------------------------------------------------------------------
            */
            $searchQuery = (clone $baseSearch)
                ->with('bus')

                ->when(
                    $request->filled('passengers'),
                    function ($query) use ($request) {
                        $query->where(
                            'available_seats',
                            '>=',
                            $request->integer('passengers')
                        );
                    }
                )

                ->when(
                    $request->filled('bus_type'),
                    function ($query) use ($request) {
                        $query->whereHas(
                            'bus',
                            function ($busQuery) use ($request) {
                                $busQuery->where(
                                    'bus_type',
                                    $request->bus_type
                                );
                            }
                        );
                    }
                )

                ->when(
                    $request->filled('max_price'),
                    function ($query) use ($request) {
                        $query->where(
                            'price',
                            '<=',
                            $request->input('max_price')
                        );
                    }
                )

                ->when(
                    $request->filled('departure_period'),
                    function ($query) use ($request) {
                        $period = $request->input('departure_period');

                        $timeRange = match ($period) {
                            'morning' => [
                                '00:00:00',
                                '11:59:59',
                            ],

                            'afternoon' => [
                                '12:00:00',
                                '17:59:59',
                            ],

                            'evening' => [
                                '18:00:00',
                                '21:59:59',
                            ],

                            'night' => [
                                '22:00:00',
                                '23:59:59',
                            ],

                            default => null,
                        };

                        if ($timeRange !== null) {
                            [$start, $end] = $timeRange;

                            $query->whereBetween(
                                'departure_time',
                                [$start, $end]
                            );
                        }
                    }
                );

            

            $routes = $searchQuery
                ->when(
                    $request->input('sort_by'),
                    function ($query, string $sortBy) {
                        [$column, $direction] = match ($sortBy) {
                            'price_asc' => ['price', 'asc'],
                            'price_desc' => ['price', 'desc'],
                            'departure_latest' => ['departure_time', 'desc'],
                            default => ['departure_time', 'asc'],
                        };

                        $query->orderBy($column, $direction);
                    },
                    function ($query) {
                        $query->orderBy('departure_time');
                    }
                )
                ->orderBy('id')
                ->paginate(10)
                ->withQueryString();
        } elseif ($hasAnySearch) {
           return back() ->withInput() ->withErrors([ 'trip' => 'Lengkapi kota asal, kota tujuan, dan tanggal keberangkatan.', ]); 
        }

        return view(
            'customer.trips.index',
            compact(
                'routes',
                'availableRoutes',
                'availableBuses',
                'matchingSchedules'
            )
        );
    }

    public function citySuggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q'));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $pattern = '%' . addcslashes($query, '%_\\') . '%';

        $cities = TravelRoute::query()
            ->where('origin_city', 'like', $pattern)
            ->select('origin_city as city')
            ->distinct()
            ->union(
                TravelRoute::query()
                    ->where('destination_city', 'like', $pattern)
                    ->select('destination_city as city')
                    ->distinct()
            )
            ->orderBy('city')
            ->limit(10)
            ->pluck('city');

        return response()->json($cities->values());
    }


    public function show(TravelRoute $route): View
    {
        $route->load(['bus', 'reviews.user']);

        abort_if(
            $route->status !== 'available'
            || $route->available_seats < 1
            || !$route->bus
            || $route->bus->status !== 'active',
            404
        );

        return view(
            'customer.trips.show',
            compact('route')
        );
    }
}