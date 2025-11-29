<div class="p-6 bg-white rounded shadow-lg">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Inventario de Equipos UFG</h2>
        
        <div class="flex gap-2 mt-4 md:mt-0">
            <input wire:model.live="search" type="text" placeholder="Buscar..." class="border rounded px-3 py-2">
            <button wire:click="create()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nuevo
            </button>
        </div>
    </div>

    <!-- PESTAÑAS DE FILTRO (Esto es lo que causaba el error si faltaba la variable) -->
    <div class="flex space-x-2 mb-4 border-b pb-2 overflow-x-auto">
        <button wire:click="$set('filter', 'all')" 
                class="px-3 py-1 rounded-full text-sm font-medium {{ $filter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Todos
        </button>
        <button wire:click="$set('filter', 'available')" 
                class="px-3 py-1 rounded-full text-sm font-medium {{ $filter === 'available' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
            Disponibles
        </button>
        <button wire:click="$set('filter', 'rented')" 
                class="px-3 py-1 rounded-full text-sm font-medium {{ $filter === 'rented' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
            Prestados
        </button>
        <button wire:click="$set('filter', 'maintenance')" 
                class="px-3 py-1 rounded-full text-sm font-medium {{ $filter === 'maintenance' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
            Mantenimiento
        </button>
    </div>

    <!-- ... El resto de tu tabla ... -->