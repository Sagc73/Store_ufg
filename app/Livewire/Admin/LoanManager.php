<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Pest\Mutate\Mutators\Equality\EqualToIdentical;

use function Pest\Laravel\session;

class LoanManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'active';

    //variables pra modal devo
    public $confirmingReturn = false;
    public $loanToReturnId;
    public $returnObservation;

    //resetar paginacion si buscamos algo
    public function updatingSearch(){
        $this->resetPage();
    }


    //accion: ABRIR MODAL DE CONFIRM DE DEVOLUCION
    public function confirmReturn($loanId){
        $this->loanToReturnId = $loanId;
        $this->confirmingReturn = true;
        $this->returnObservation = '';
    }

    //ACCION: procesar la devo de forma logic critica
    public function processReturn(){
        //usando transaccion para segurar integridad de datos
        DB::transaction(function(){
            $loan = Loan::findOrFail($this->loanToReturnId);
            //actualizar el prestamo
            $loan->update([
                'status' => 'returned',
                'returned_date' => now(),
                'observations' => $this->returnObservation ? $loan->observations." | Devolution: " . $this->returnObservation : $loan->observations
            ]);

            //liberando el equipo, cambia de estado a disponible
            $equipment = Equipment::findOrFail($loan->equipment_id);
            $equipment->update([
                'status' => 'available'
            ]);
        });
        $this->confirmingReturn = false;
        //session()->flash('message','Equipo devuelto, actualizado como disponible');
    }

    
    public function render()
    {   //consulta base con relaciones
        $query = Loan::with(['user','equipment'])
                ->where(function ($q) {
                    $q->whereHas('user', function ($subQ) {
                        $subQ->where('name','like','%'. $this->search.'%');
                    })
                    ->orwhereHas('equipment',function($subQ){
                        $subQ->where('name','like','%'.$this->search.'%')
                                ->orWhere('serial_number','like','%'.$this->search.'%');
                    });
                });
        //filtros de estado
        if($this->filter === 'active'){
            $query->where('status', 'active');
        }else if($this->filter === 'late'){
            $query->where('status', 'active')
                    ->where('expected_return_date','<',now());
        }else if($this->filter === 'history'){
            $query->where('status','returned');
        }

        $loans = $query->orderBy('created_at','desc')->paginate(12);
        return view('livewire.admin.loan-manager',[
            'loans' => $loans
        ]);
    }
}
