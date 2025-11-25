<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $descripcion;
    public $stock = 0;
    public $price;
    
    public function store(){
        $product = new Product();
        $product->name = $this->name;
        $product->stock = $this->stock;
        $product->price = $this->price;
        $product->description = $this->descripcion;
        $product->save();
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
