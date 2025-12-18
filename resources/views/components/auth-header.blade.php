@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center items-center">
    @if(file_exists(public_path('logo.png')))
        <img src="{{ asset('logo.png') }}" alt="RUMBLE Logo" class="h-24 mb-4">
    @elseif(file_exists(public_path('logo.svg')))
        <img src="{{ asset('logo.svg') }}" alt="RUMBLE Logo" class="h-24 mb-4">
    @endif
    <flux:heading size="xl">{{ $title }}</flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>
