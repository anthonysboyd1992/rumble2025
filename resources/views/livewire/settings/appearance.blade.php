<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Dark mode is always enabled')">
        <p class="text-zinc-400">This application uses dark mode only.</p>
    </x-settings.layout>
</section>
