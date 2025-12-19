<div 
    wire:poll.2s
    class="flex items-center justify-center"
    style="width: 100%; height: 100%;"
>
    <div class="text-center" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div class="mb-16 font-black" style="font-family: 'Consolas', 'Monaco', monospace; letter-spacing: 0.3em; font-size: clamp(4rem, 10vw, 10rem); color: #ffffff; text-align: center;">
            EVENT
        </div>
        <div class="font-black leading-none" style="font-family: 'Consolas', 'Monaco', monospace; font-size: clamp(15rem, 40vw, 50rem); color: #ffffff; text-align: center;">
            {{ $eventNumber }}
        </div>
    </div>
</div>

