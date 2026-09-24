<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Bus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BusController extends Controller
{
    public function index(): View
    {
        $buses = Bus::latest()->paginate(10);

        return view('admin.buses.index', compact('buses'));
    }

    public function create(): View
    {
        return view('admin.buses.create');
    }

    public function store(StoreBusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('buses', 'public');
        }

        Bus::create($data);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Data bus berhasil ditambahkan.');
    }

    public function show(Bus $bus): View
    {
        $bus->load('routes');

        return view('admin.buses.show', compact('bus'));
    }

    public function edit(Bus $bus): View
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(
        UpdateBusRequest $request,
        Bus $bus
    ): RedirectResponse {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($bus->image) {
                Storage::disk('public')->delete($bus->image);
            }

            $data['image'] = $request->file('image')
                ->store('buses', 'public');
        }

        $bus->update($data);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Data bus berhasil diperbarui.');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->routes()->exists()) {
            return redirect()
                ->route('admin.buses.index')
                ->with('error', 'Bus tidak dapat dihapus karena sudah memiliki rute.');
        }

        if ($bus->image) {
            Storage::disk('public')->delete($bus->image);
        }

        $bus->delete();

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Data bus berhasil dihapus.');
    }
}
