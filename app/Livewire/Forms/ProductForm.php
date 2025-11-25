<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Product;
use Livewire\Form;

class ProductForm extends Form
{
     //otras formas de validacion de variables
    #[Validate('required|string|max:255', as:'Debe Escribir su nombre')]
    public $name;

    #[Validate('nullable|string|max:1000')]
    public $description;

    #[Validate('required|integer|min:0', as:'El stock solo numeros enteros positivos')]
    public $stock = 0;

    #[Validate('required|numeric|min:0', as:'¡No se admiten caracteres alfabeticos.!')]
    public $price;

    public function store(){
              
        $this->validate();
        //VALIDANDO LA DATA DESDE VARIABLES GLOb
        Product::create([
            'name' => $this->name,
            'stock' => $this->stock,
            'price' => $this->price,
            'description' => $this->description,    
        ]);

    }
}
