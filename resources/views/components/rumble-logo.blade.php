<div class="flex items-center gap-3">
    @if(file_exists(public_path('logo.png')))
        <img src="{{ asset('logo.png') }}" alt="RUMBLE Logo" class="rumble-logo">
    @elseif(file_exists(public_path('logo.svg')))
        <img src="{{ asset('logo.svg') }}" alt="RUMBLE Logo" class="rumble-logo">
    @else
        <div class="flex items-center gap-2">
            <div class="text-4xl font-black rumble-blue tracking-tight">RUMBLE</div>
            <div class="text-xl rumble-text-muted">in Fort Wayne</div>
        </div>
    @endif
</div>

