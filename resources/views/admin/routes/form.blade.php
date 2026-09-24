@csrf
@if(isset($route)) @method('PUT') @endif
<div class="grid gap-4 md:grid-cols-2">
    <label>Bus<select name="bus_id" class="mt-1 w-full rounded border-gray-300">@foreach($buses as $bus)<option value="{{ $bus->id }}" @selected(old('bus_id', $route->bus_id ?? '') == $bus->id)>{{ $bus->bus_name }} ({{ $bus->plate_number }})</option>@endforeach</select></label>
    @foreach(['origin_city' => 'Kota Asal', 'origin_terminal' => 'Terminal Asal', 'destination_city' => 'Kota Tujuan', 'destination_terminal' => 'Terminal Tujuan'] as $field => $label)<label>{{ $label }}<input name="{{ $field }}" value="{{ old($field, $route->$field ?? '') }}" class="mt-1 w-full rounded border-gray-300" required></label>@endforeach
    <label>Tanggal<input type="date" name="departure_date" value="{{ old('departure_date', isset($route) ? $route->departure_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded border-gray-300" required></label>
    <label>Waktu Berangkat<input type="time" name="departure_time" value="{{ old('departure_time', isset($route) ? substr($route->departure_time, 0, 5) : '') }}" class="mt-1 w-full rounded border-gray-300" required></label>
    <label>Estimasi Tiba<input type="time" name="estimated_arrival_time" value="{{ old('estimated_arrival_time', isset($route) ? substr($route->estimated_arrival_time, 0, 5) : '') }}" class="mt-1 w-full rounded border-gray-300" required></label>
    <label>Harga<input type="number" min="0" name="price" value="{{ old('price', $route->price ?? '') }}" class="mt-1 w-full rounded border-gray-300" required></label>
    <label>Kursi Tersedia<input type="number" min="0" name="available_seats" value="{{ old('available_seats', $route->available_seats ?? '') }}" class="mt-1 w-full rounded border-gray-300" required></label>
    <label>Status<select name="status" class="mt-1 w-full rounded border-gray-300">@foreach(['available','full','cancelled'] as $status)<option value="{{ $status }}" @selected(old('status', $route->status ?? 'available') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
</div>
<button class="mt-6 rounded-lg bg-indigo-600 px-4 py-2 text-white">Simpan</button>
