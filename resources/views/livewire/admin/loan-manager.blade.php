<div class="p-6 bg-gray-50 min-h-screen">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Préstamos y Devoluciones</h2>
        
        <!-- Buscador -->
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
            <input wire:model.live="search" type="text" placeholder="Buscar por estudiante, equipo o serie..." 
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>
    </div>

    <!-- Filtros / Pestañas -->
    <div class="flex space-x-4 mb-6 border-b border-gray-200">
        <button wire:click="$set('filter', 'active')" 
                class="pb-2 px-4 font-medium {{ $filter === 'active' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
            Préstamos Activos
        </button>
        <button wire:click="$set('filter', 'late')" 
                class="pb-2 px-4 font-medium {{ $filter === 'late' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-red-500' }}">
            En Mora (Atrasados)
        </button>
        <button wire:click="$set('filter', 'history')" 
                class="pb-2 px-4 font-medium {{ $filter === 'history' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700' }}">
            Historial de Devoluciones
        </button>
    </div>

    <!-- Mensaje de Éxito -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 transition">
                    <!-- Columna Estudiante -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($loan->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $loan->user->name ?? 'Usuario Eliminado' }}</div>
                                <div class="text-sm text-gray-500">{{ $loan->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Columna Equipo -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-bold">{{ $loan->equipment->name ?? 'Equipo Eliminado' }}</div>
                        <div class="text-xs text-gray-500">SN: {{ $loan->equipment->serial_number ?? 'N/A' }}</div>
                    </td>

                    <!-- Columna Fechas -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs text-gray-500">Salida: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}</div>
                        <div class="text-sm font-bold {{ $loan->is_overdue ? 'text-red-600' : 'text-gray-700' }}">
                            Devolución: {{ \Carbon\Carbon::parse($loan->expected_return_date)->format('d/m/Y H:i') }}
                        </div>
                        @if($loan->returned_date)
                            <div class="text-xs text-green-600 mt-1">Entregado: {{ \Carbon\Carbon::parse($loan->returned_date)->format('d/m/Y') }}</div>
                        @endif
                    </td>

                    <!-- Columna Estado -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($loan->status == 'returned')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Devuelto
                            </span>
                        @elseif($loan->is_overdue)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                ¡EN MORA!
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Activo
                            </span>
                        @endif
                    </td>

                    <!-- Columna Acciones -->
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($loan->status !== 'returned')
                            <button wire:click="confirmReturn({{ $loan->id }})" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-bold shadow">
                                Registrar Devolución
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Completado</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        No se encontraron préstamos con los filtros actuales.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $loans->links() }}
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN DE DEVOLUCIÓN -->
    @if($confirmingReturn)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icono Check -->
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmar Devolución de Equipo
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿El estudiante ha entregado el equipo? Esto marcará el equipo como <b>"Disponible"</b> nuevamente en el inventario.
                                </p>
                                
                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Observaciones (Opcional)</label>
                                    <textarea wire:model="returnObservation" 
                                              class="w-full border rounded p-2 text-sm" 
                                              placeholder="Ej: Se devuelve sin cargador, pantalla sucia, etc."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="processReturn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar Devolución
                    </button>
                    <button wire:click="$set('confirmingReturn', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>