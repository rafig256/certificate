<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::section heading="کیف پول">
        {{ $this->walletInfolist }}
    </x-filament::section>

    <x-filament::section heading="تراکنش‌ها">
        {{ $this->table }}
    </x-filament::section>

    <x-filament::button
        wire:click="save"
        class="mt-4"
        color="primary"
    >
        ذخیره
    </x-filament::button>
</x-filament-panels::page>
