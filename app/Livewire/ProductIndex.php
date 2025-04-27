<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Product;

class ProductIndex extends Component
{
    public $name, $description, $price;
    public $productId;

protected $rules = [
    'name' => 'required|string|max:255',
    'description' => 'required|string|max:1000',
    'price' => 'required|numeric|min:0',
];    

public function render()
    {
        $data['products'] = Product::paginate(5);
        return view('livewire.product-index', $data);
    }

    public function save()
    {
        $this->validate();
        $input['name'] = $this->name;
        $input['description'] = $this->description;
        $input['price'] = $this->price;
        if($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($input);
            session()->flash('message', 'Product successfully updated!');
            $this->dispatch('save', message: 'Product successfully updated!');
        } else {
            Product::create($input);
            session()->flash('message', 'Product successfully added!');
            $this->dispatch('save' , message: 'Product successfully added!');
        }
        // Reset form fields if needed
        $this->reset(['name', 'description', 'price']);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->productId = $product->id;
    }

    #[On('delete')]
    public function delete($id){
        $product = Product::findOrFail($id);
        $product->delete();
        session()->flash('message', 'Product successfully deleted!');
        $this->dispatch('save', message: 'Product successfully deleted!');
    }
}
