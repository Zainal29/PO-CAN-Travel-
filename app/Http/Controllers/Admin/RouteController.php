<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Models\Bus;
use App\Models\TravelRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = TravelRoute::with('bus')
            ->latest('departure_date')
            ->latest('departure_time')
            ->paginate(10);

        return view('admin.routes.index', compact('routes'));
    }

    public function create(): View
    {
        $buses = Bus::where('status', 'active')
            ->orderBy('bus_name')
            ->get();

        return view('admin.routes.create', compact('buses'));
    }

    public function store(StoreRouteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $bus = Bus::findOrFail($data['bus_id']);

        if ($data['available_seats'] > $bus->total_seats) {
            return back()
                ->withInput()
                ->withErrors([
                    'available_seats' =>
                        'Jumlah kursi tersedia melebihi kapasitas bus.',
                ]);
        }

        TravelRoute::create($data);

        return redirect()
            ->route('admin.routes.index')
            ->with('success', 'Rute berhasil ditambahkan.');
    }

    public function show(TravelRoute $route): View
    {
        $route->load('bus');

        return view('admin.routes.show', compact('route'));
    }

    public function edit(TravelRoute $route): View
    {
        $buses = Bus::where('status', 'active')
            ->orderBy('bus_name')
            ->get();

        return view('admin.routes.edit', compact('route', 'buses'));
    }

    public function update(
        UpdateRouteRequest $request,
        TravelRoute $route
    ): RedirectResponse {
        $data = $request->validated();

        $bus = Bus::findOrFail($data['bus_id']);

        $bookedSeats = $route->orders()
            ->whereIn('order_status', ['pending', 'paid'])
            ->sum('total_passengers');

        if ($data['available_seats'] > $bus->total_seats) {
            return back()
                ->withInput()
                ->withErrors([
                    'available_seats' =>
                        'Jumlah kursi tersedia melebihi kapasitas bus.',
                ]);
        }

        if ($data['available_seats'] + $bookedSeats > $bus->total_seats) {
            return back()
                ->withInput()
                ->withErrors([
                    'available_seats' =>
                        'Kursi tersedia ditambah kursi yang sudah dipesan tidak boleh melebihi kapasitas bus.',
                ]);
        }

        $route->update($data);

        return redirect()
            ->route('admin.routes.index')
            ->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroy(TravelRoute $route): RedirectResponse
    {
        if ($route->orders()->exists()) {
            return redirect()
                ->route('admin.routes.index')
                ->with(
                    'error',
                    'Rute tidak dapat dihapus karena sudah memiliki order.'
                );
        }

        $route->delete();

        return redirect()
            ->route('admin.routes.index')
            ->with('success', 'Rute berhasil dihapus.');
    }
}
