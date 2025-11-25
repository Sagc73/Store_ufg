<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{   
    public ProductForm $form; 
    public function save(){
        $this->form->store();
        /*ANTES DE REDIRIGIR HACER LA NOTIFICACIÓN FLASH*/
        //SUCCESS ES LA VARIABLE PARA INVOCAR EN EL FRONTEND
        session()->flash('success','Registro guardado con exito.!');
        $this->redirectRoute('products.index', navigate:true);
    }
    public function render()
    {
        return view('livewire.products.create');
    }
}
