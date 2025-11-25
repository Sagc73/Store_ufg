<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Component;

class Update extends Component
{

    public ProductForm $form;
    public function mount(Product $product){
        $this->form->setProduct($product);
    }

    //FUNCION PARA EDITAR Y GUARDAR
    public function save(){
        $this->form->update();

        session()->flash('success','Registro actualizado con exito.!');
        $this->redirectRoute('products.index', navigate:true);
    }
    public function render()
    {
        return view('livewire.products.create');
    }
}
