<x-filament::page>
    {{ $this->form }}

    <x-filament::button wire:click="send">
        Send Email
    </x-filament::button>
</x-filament::page>