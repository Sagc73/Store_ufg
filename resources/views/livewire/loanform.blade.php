<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Solicitud de Equipo UFG</h2>

    <!-- Mensajes de éxito -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        
        <!-- 1. Buscador y Selección de Equipo -->
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Seleccione un equipo</label>
            
            <input wire:model.live="search" type="text" placeholder="Buscar (ej: Proyector Sony...)" 
                   class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto">
                @foreach($equipments as $eq)
                    <label class="cursor-pointer border p-4 rounded hover:bg-blue-50 {{ $equipment_id == $eq->id ? 'bg-blue-100 border-blue-500 ring-2 ring-blue-500' : '' }}">
                        <input type="radio" wire:model="equipment_id" value="{{ $eq->id }}" class="hidden">
                        <div class="font-bold text-gray-800">{{ $eq->name }}</div>
                        <div class="text-sm text-gray-600">{{ $eq->brand }} - {{ $eq->model }}</div>
                        <span class="inline-block bg-green-200 text-green-800 text-xs px-2 rounded-full mt-2">
                            Disponible
                        </span>
                    </label>
                @endforeach
            </div>
            @error('equipment_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- 2. Fecha de Devolución -->
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Fecha estimada de devolución</label>
            <input wire:model="expected_return_date" type="datetime-local" 
                   class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
            @error('expected_return_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition">
            Confirmar Préstamo
        </button>
    </form>
</div>