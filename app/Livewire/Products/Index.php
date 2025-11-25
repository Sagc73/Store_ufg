<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; 
    
    public function render()
    {
        return view('livewire.products.index',[
            'products' => Product::paginate(20)
        ]);
    }
}
