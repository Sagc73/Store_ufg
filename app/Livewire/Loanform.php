<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Loanform extends Component
{
    public $equipment_id;
    public $expected_return_date;
    public $search = '';//buscar equipos en tiempo real

    //reglas de validacion
    protected $rules = [
        'equipment_id'=>'required|exists:equiment,id',
        'expected_return_date'=> 'required|date|after:today',
    ];

    public function save(){
        $this->validate();

        //seguridad transaccion de datos
        DB::transaction(function (){
            $equipment = Equipment::lockForUpdate()->find($this->equipment_id);

            if($equipment->status !== 'available'){
            $this->addError('equipment_id', 'Este equipo ya no está disponible');
            return;
            }

            //creando prestamo
            Loan::create([
                'user_id' => Auth::id(),
                'equipment_id' => $this->equipment_id,
                'loan_date' => now(),
                'expected_return_date' => $this->expected_return_date,
                'status' => 'active'
            ]);

            //actualizar estado del eq
            $equipment->update(['status' => 'rented']);
        });

        session()->flash('message',
        '¡prestamo realizado con éxito!');
        return redirect()->route('dashboard');
        
    }

    public function render()
    {
        //consulta segura para mostrar los eq disponibles
        $equipments = Equipment::where('status','available')
            ->where('name','like','%'.$this->search.'%')
            ->get();
        return view('livewire.loanform', compact('equipments'));
    }
}
