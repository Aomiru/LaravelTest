<div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="flex justify-end">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 1000)"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="rounded-xl fixed top-4 right-4 bg-green-500 text-white px-4 py-2 shadow-lg">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="flex flex-col gap-6">
        <flux:heading class="px-10" size="xl">
            {{ $productId ? 'Edit Form' : 'Add Form' }}
        </flux:heading>
        <div class="rounded-xl border px-10 py-8">
            <form wire:submit.prevent="save" class="space-y-4 mb-6">
                <div class="grid grid-col-4 gap-4">
                    <flux:input wire:model="name" label="Product Name" placeholder="Enter product name" />
                    <flux:textarea wire:model="description" label="Product Description"
                        placeholder="Enter product description" />
                    <flux:input wire:model="price" label="Product Price" placeholder="Enter product price" />
                    <flux:button type="Submit" class="w-full">Add Product</flux:button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <div class="rounded-xl border px-10 py-8">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Product Name</th>
                        <th class="text-left">Description</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($products as $index => $product)
                        <tr class="border-b space-y-4">
                            <td class="py-2">{{ $index + 1 }}</td>
                            <td class="py-2">{{ $product->name }}</td>
                            <td class="py-2">{{ $product->description }}</td>
                            <td class="py-2">{{ $product->price }}</td>
                            <td class="py-2">
                                <flux:button wire:click="edit({{ $product->id }})" variant="primary"
                                    icon="pencil-square" class="text-blue-500">Edit</flux:button>
                                <flux:button wire:click="$dispatch('confirmDelete', {{ $product->id }})"
                                    variant="danger" icon="trash" class="text-red-500">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center p-2">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('save', function(res) {
            Swal.fire('Success!', res.message, 'success');
        });
       
        Livewire.on('confirmDelete', function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', {
                        id: id
                    });
                }
            })
        });
    });
</script>
