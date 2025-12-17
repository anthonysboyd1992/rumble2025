<div 
    x-data="{ 
        currentTime: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }),
        init() {
            setInterval(() => this.currentTime = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }), 1000);
        }
    }"
    wire:poll.2s
    class="min-h-screen flex flex-col"
    style="background: #000; color: #fff; font-family: 'Consolas', 'Monaco', monospace; font-size: 14px;"
>
    {{-- Header Bar --}}
    <header class="flex-none flex items-center justify-between px-4 py-1.5" style="background: #000; border-bottom: 1px solid #333;">
        <div class="flex items-center gap-4">
            <span style="color: #888;">Session:</span>
            <span class="font-bold" style="color: #fff;">Practice {{ ucfirst($dayFilter) }}</span>
            <span style="color: #333;">|</span>
            <span style="color: #888;">Class:</span>
            <span class="font-bold" style="color: #fff;">{{ $this->currentClassName }}</span>
            
            <div class="flex gap-1 ml-2">
                @foreach(['thursday' => 'THU', 'friday' => 'FRI', 'saturday' => 'SAT'] as $day => $label)
                    <button 
                        wire:click="$set('dayFilter', '{{ $day }}')"
                        class="px-2.5 py-0.5 text-xs font-bold"
                        style="{{ $dayFilter === $day ? 'background: #22c55e; color: #000;' : 'background: #222; color: #555;' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>

            @if($this->classes->count() > 1)
                <div class="flex gap-1">
                    @foreach($this->classes as $class)
                        <button 
                            wire:click="$set('classFilter', {{ $class->id }})"
                            class="px-2.5 py-0.5 text-xs font-bold"
                            style="{{ $classFilter === $class->id ? 'background: #22c55e; color: #000;' : 'background: #222; color: #555;' }}"
                        >{{ $class->name }}</button>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="flex items-center gap-4">
            <span style="color: #555;">{{ $this->standings->count() }} drivers</span>
            <span class="text-lg font-bold" style="color: #fff;" x-text="currentTime"></span>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 flex" style="background: #000;">
        @if($this->standings->isEmpty())
            <div class="flex-1 flex items-center justify-center">
                <div style="color: #444;">Waiting for session data...</div>
            </div>
        @else
            @php 
                $leaderSeconds = null;
                $firstStanding = $this->standings->first();
                if ($firstStanding) {
                    $leaderSeconds = \App\Helpers\TimeFormatter::parseTimeToSeconds($firstStanding['best_time']);
                }
                $leftColumn = $this->standings->take(20)->values();
                $rightColumn = $this->standings->slice(20, 20)->values();
            @endphp
            
            {{-- Timing Grid --}}
            <div class="flex flex-1 {{ count($expandedDrivers) > 0 ? '' : '' }}">
                {{-- Left Column --}}
                <div class="flex-1 flex flex-col" style="border-right: 1px solid #333;">
                    <div class="flex items-center px-3 py-1" style="background: #111; color: #666; border-bottom: 1px solid #333; font-size: 11px;">
                        <div style="width: 30px;">POS</div>
                        <div style="flex: 1;">DRIVER</div>
                        <div style="width: 40px; text-align: center;">LAPS</div>
                        <div style="width: 90px; text-align: right;">BEST</div>
                        <div style="width: 80px; text-align: right;">DIFF</div>
                    </div>
                    
                    @foreach($leftColumn as $index => $standing)
                        @php
                            $seconds = \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
                            $gap = $index === 0 ? null : '+' . number_format($seconds - $leaderSeconds, 3);
                            $isExpanded = in_array($index, $expandedDrivers);
                        @endphp
                        <div 
                            wire:click="toggleDriver({{ $index }})"
                            class="flex items-center px-3 cursor-pointer hover:bg-zinc-900"
                            style="
                                height: 32px;
                                background: {{ $isExpanded ? '#1a1a1a' : ($index % 2 === 0 ? '#000' : '#080808') }};
                                border-bottom: 1px solid #1a1a1a;
                                border-left: 2px solid {{ $isExpanded ? '#22c55e' : ($index === 0 ? '#22c55e' : 'transparent') }};
                            "
                        >
                            <div style="width: 30px; color: #fff; font-weight: bold;">{{ $index + 1 }}</div>
                            <div style="flex: 1;" class="flex items-center gap-2 truncate">
                                <span style="color: #22c55e; font-weight: bold;">#{{ $standing['car_number'] }}</span>
                                <span style="color: #fff;" class="truncate">{{ strtoupper($standing['driver_name']) }}</span>
                            </div>
                            <div style="width: 40px; text-align: center; color: #22c55e;">{{ $standing['lap_count'] }}</div>
                            <div style="width: 90px; text-align: right; color: #22c55e; font-weight: bold;">{{ \App\Helpers\TimeFormatter::format($standing['best_time']) }}</div>
                            <div style="width: 80px; text-align: right; color: #888;">{{ $gap ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Right Column --}}
                <div class="flex-1 flex flex-col" style="border-right: 1px solid #333;">
                    <div class="flex items-center px-3 py-1" style="background: #111; color: #666; border-bottom: 1px solid #333; font-size: 11px;">
                        <div style="width: 30px;">POS</div>
                        <div style="flex: 1;">DRIVER</div>
                        <div style="width: 40px; text-align: center;">LAPS</div>
                        <div style="width: 90px; text-align: right;">BEST</div>
                        <div style="width: 80px; text-align: right;">DIFF</div>
                    </div>
                    
                    @foreach($rightColumn as $index => $standing)
                        @php
                            $realIndex = $index + 20;
                            $seconds = \App\Helpers\TimeFormatter::parseTimeToSeconds($standing['best_time']);
                            $gap = '+' . number_format($seconds - $leaderSeconds, 3);
                            $isExpanded = in_array($realIndex, $expandedDrivers);
                        @endphp
                        <div 
                            wire:click="toggleDriver({{ $realIndex }})"
                            class="flex items-center px-3 cursor-pointer hover:bg-zinc-900"
                            style="
                                height: 32px;
                                background: {{ $isExpanded ? '#1a1a1a' : ($index % 2 === 0 ? '#000' : '#080808') }};
                                border-bottom: 1px solid #1a1a1a;
                                border-left: 2px solid {{ $isExpanded ? '#22c55e' : 'transparent' }};
                            "
                        >
                            <div style="width: 30px; color: #fff; font-weight: bold;">{{ $realIndex + 1 }}</div>
                            <div style="flex: 1;" class="flex items-center gap-2 truncate">
                                <span style="color: #22c55e; font-weight: bold;">#{{ $standing['car_number'] }}</span>
                                <span style="color: #fff;" class="truncate">{{ strtoupper($standing['driver_name']) }}</span>
                            </div>
                            <div style="width: 40px; text-align: center; color: #22c55e;">{{ $standing['lap_count'] }}</div>
                            <div style="width: 90px; text-align: right; color: #22c55e; font-weight: bold;">{{ \App\Helpers\TimeFormatter::format($standing['best_time']) }}</div>
                            <div style="width: 80px; text-align: right; color: #888;">{{ $gap }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Side Panel for Expanded Drivers --}}
            @if(count($expandedDrivers) > 0)
                <div class="flex flex-col overflow-y-auto" style="width: 320px; background: #0a0a0a; border-left: 1px solid #333;">
                    <div class="px-3 py-2" style="background: #111; border-bottom: 1px solid #333;">
                        <span style="color: #666; font-size: 11px;">DRIVER DETAILS</span>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto">
                        @foreach($expandedDrivers as $driverIndex)
                            @php
                                $standing = $this->standings[$driverIndex] ?? null;
                            @endphp
                            @if($standing)
                                <div style="border-bottom: 1px solid #222; padding: 10px;">
                                    {{-- Driver header --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span style="color: #22c55e; font-weight: bold;">#{{ $standing['car_number'] }}</span>
                                            <span style="color: #fff; font-weight: bold;">{{ strtoupper($standing['driver_name']) }}</span>
                                        </div>
                                        <button 
                                            wire:click="toggleDriver({{ $driverIndex }})"
                                            style="color: #555; font-size: 16px;"
                                        >&times;</button>
                                    </div>
                                    
                                    {{-- Session cards --}}
                                    <div class="flex flex-col gap-2">
                                        @foreach($standing['times_by_session'] as $session)
                                            <div style="background: #151515; border: 1px solid #2a2a2a; border-radius: 4px; padding: 6px 8px;">
                                                <div style="color: #22c55e; font-size: 10px; font-weight: bold; margin-bottom: 4px;">
                                                    {{ $session['session'] }}
                                                </div>
                                                <div class="flex flex-col gap-0.5">
                                                    @foreach($session['times'] as $time)
                                                        <div class="flex items-center justify-between" style="font-size: 11px;">
                                                            @if($time['lap'])
                                                                <span style="color: #555;">Lap {{ $time['lap'] }}</span>
                                                            @else
                                                                <span style="color: #555;">—</span>
                                                            @endif
                                                            <span style="color: {{ $time['is_best'] ? '#22c55e' : '#888' }}; font-weight: {{ $time['is_best'] ? 'bold' : 'normal' }};">
                                                                {{ \App\Helpers\TimeFormatter::format($time['time']) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </main>
</div>
