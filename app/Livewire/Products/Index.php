<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; 
    
    public function delete(Product $product){
        $product->delete();

        session()->flash('success', 'Producto eliminado con exito.!');
        $this->redirectRoute('products.index', navigate:true);


    }

    public function render()
    {
        return view('livewire.products.index',[
            'products' => Product::paginate(15)
        ]);
    }
}
