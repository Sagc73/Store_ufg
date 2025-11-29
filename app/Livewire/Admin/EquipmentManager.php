<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Equipment;
use Illuminate\Support\Facades\Storage;

class EquipmentManager extends Component
{
    use WithPagination, WithFileUploads;

    // --- AGREGAR ESTA VARIABLE ---
    public $filter = 'all'; // 'all', 'available', 'rented', 'maintenance'
    
    public $search = '';
    public $name, $brand, $model, $serial_number, $category, $description, $status = 'available';
    public $image, $current_image, $equipment_id;
    public $isIsOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'brand' => 'required',
            'serial_number' => 'required|unique:equipment,serial_number,' . $this->equipment_id,
            'category' => 'required',
            'status' => 'required',
            'image' => 'nullable|image|max:2048',
        ];
    }

    // Resetear paginación al buscar o filtrar
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    public function render()
    {
        // Consulta base
        $query = Equipment::query();

        // 1. Aplicar Buscador
        $query->where(function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('serial_number', 'like', '%' . $this->search . '%')
              ->orWhere('category', 'like', '%' . $this->search . '%');
        });

        // 2. Aplicar Filtro (ESTO SOLUCIONA EL ERROR Y AGREGA FUNCIONALIDAD)
        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $equipments = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.equipment-manager', compact('equipments'));
    }

    // ... (Mantén el resto de tus funciones create, store, edit, delete igual que antes)
    
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isIsOpen = true;
    }

    public function closeModal()
    {
        $this->isIsOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->brand = '';
        $this->model = '';
        $this->serial_number = '';
        $this->category = '';
        $this->description = '';
        $this->status = 'available';
        $this->image = null;
        $this->current_image = null;
        $this->equipment_id = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'category' => $this->category,
            'description' => $this->description,
            'status' => $this->status,
        ];

        if ($this->image) {
            $imageName = $this->image->store('equipment_photos', 'public');
            $data['image_path'] = $imageName;
        }

        Equipment::updateOrCreate(['id' => $this->equipment_id], $data);

        session()->flash('message', $this->equipment_id ? 'Equipo actualizado.' : 'Equipo creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $this->equipment_id = $id;
        $this->name = $equipment->name;
        $this->brand = $equipment->brand;
        $this->model = $equipment->model;
        $this->serial_number = $equipment->serial_number;
        $this->category = $equipment->category;
        $this->description = $equipment->description;
        $this->status = $equipment->status;
        $this->current_image = $equipment->image_path;

        $this->openModal();
    }

    public function delete($id)
    {
        $equipment = Equipment::find($id);
        
        if($equipment->status === 'rented') {
            session()->flash('error', 'No puedes eliminar un equipo que está prestado actualmente.');
            return;
        }

        if ($equipment->image_path) {
            Storage::disk('public')->delete($equipment->image_path);
        }
        
        $equipment->delete();
        session()->flash('message', 'Equipo eliminado correctamente.');
    }
}