<div 
    wire:poll.2s
    class="flex items-center justify-center"
    style="width: 100%; height: 100%;"
>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <div class="mb-8 font-black" style="font-family: 'Consolas', 'Monaco', monospace; letter-spacing: 0.3em; font-size: clamp(3rem, 8vw, 8rem); color: #ffffff; text-align: center; line-height: 1;">
            EVENT
        </div>
        <div class="font-black leading-none mb-8" style="font-family: 'Consolas', 'Monaco', monospace; font-size: clamp(10rem, 30vw, 35rem); color: #ffffff; text-align: center; line-height: 1;">
            {{ $eventNumber }}
        </div>
        @if($message)
            <div class="font-bold" style="font-family: 'Consolas', 'Monaco', monospace; font-size: clamp(1.5rem, 4vw, 3rem); color: #ffffff; text-align: center; line-height: 1.2; text-transform: uppercase;">
                {{ $message }}
            </div>
        @endif
    </div>
</div>

