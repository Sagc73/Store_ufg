<div>
    <form wire:submit='save' class="space-y-4">
        <x-form.input wire:model="form.name" label="Nombre" type="text" name="form.name" placeholder="Ingrese nombre del producto" />
        <x-form.input wire:model="form.stock" label="Stock" type="text" name="form.stock" placeholder="Cantidad existente" />
        <x-form.input  wire:model="form.price" label="Precio" type="text" name="form.price" placeholder="Ingrese nombre del producto" />
        <div class="flex w-full max-w-md flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="description" class="w-fit pl-0.5 text-sm">Descripción</label>
            <textarea wire:model = 'form.description' id="form.description"
                class="w-full rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                rows="3" placeholder="Descripción......."></textarea>
        </div>
        <button type="submit"
            class="whitespace-nowrap rounded-radius bg-secondary border border-secondary px-4 py-2 text-xs font-medium tracking-wide text-on-secondary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-secondary-dark dark:border-secondary-dark dark:text-on-secondary-dark dark:focus-visible:outline-secondary-dark">Crear</button>
    </form>
</div>
