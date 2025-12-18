<div 
    x-data="{ 
        currentTime: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }),
        previousData: {},
        changedRows: [],
        init() {
            setInterval(() => this.currentTime = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }), 1000);
        if (typeof Echo !== 'undefined') {
            Echo.channel('standings').listen('.updated', () => $wire.$refresh());
            }
            this.storeCurrentData();
            
            Livewire.hook('morph.updated', ({ el, component }) => {
                this.detectChanges();
            });
        },
        storeCurrentData() {
            document.querySelectorAll('[data-driver-id]').forEach(el => {
                const id = el.dataset.driverId;
                const points = el.dataset.points;
                this.previousData[id] = points;
            });
        },
        detectChanges() {
            this.changedRows = [];
            document.querySelectorAll('[data-driver-id]').forEach(el => {
                const id = el.dataset.driverId;
                const points = el.dataset.points;
                if (this.previousData[id] && this.previousData[id] !== points) {
                    this.changedRows.push(id);
                    el.classList.add('row-updated');
                    setTimeout(() => {
                        el.classList.remove('row-updated');
                    }, 2000);
                }
            });
            this.storeCurrentData();
        }
    }"
    wire:poll.5s
    class="min-h-screen flex flex-col"
    style="background: #000; color: #fff; font-family: 'Consolas', 'Monaco', monospace; font-size: 14px;"
>
    <style>
        @keyframes rowFlash {
            0% { background: #22c55e !important; }
            100% { background: inherit; }
        }
        .row-updated {
            animation: rowFlash 2s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        [data-driver-id] {
            animation: slideIn 0.3s ease-out;
        }
    </style>
    {{-- Header Bar --}}
    <header class="flex-none flex items-center justify-between px-4 py-1.5" style="background: #000; border-bottom: 1px solid #333;">
        <div class="flex items-center gap-4">
            <span style="color: #888;">Standings:</span>
            <span class="font-bold" style="color: #fff;">{{ ucfirst($dayFilter ?: 'All Days') }}</span>
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
                <button 
                    wire:click="$set('dayFilter', '')"
                    class="px-2.5 py-0.5 text-xs font-bold"
                    style="{{ $dayFilter === '' ? 'background: #22c55e; color: #000;' : 'background: #222; color: #555;' }}"
                >ALL</button>
            </div>
            
            <div class="flex gap-1">
                @foreach($this->classes as $class)
                    <button 
                        wire:click="$set('classFilter', {{ $class->id }})"
                        class="px-2.5 py-0.5 text-xs font-bold"
                        style="{{ $classFilter === $class->id ? 'background: #22c55e; color: #000;' : 'background: #222; color: #555;' }}"
                    >{{ $class->name }}</button>
                @endforeach
            </div>
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
                <div style="color: #444;">Waiting for results...</div>
            </div>
        @else
            @php 
                $leftColumn = $this->standings->take(20)->values();
                $rightColumn = $this->standings->slice(20, 20)->values();
            @endphp
            
            {{-- Timing Grid --}}
            <div class="flex flex-1">
                {{-- Left Column --}}
                <div class="flex-1 flex flex-col" style="border-right: 1px solid #333;">
                    <div class="flex items-center px-3 py-1" style="background: #111; color: #666; border-bottom: 1px solid #333; font-size: 11px;">
                        <div style="width: 30px;">POS</div>
                        <div style="flex: 1;">DRIVER</div>
                        <div style="width: 70px; text-align: right;">TIME</div>
                        <div style="width: 45px; text-align: right;">QUAL</div>
                        <div style="width: 45px; text-align: right;">HEAT</div>
                        <div style="width: 45px; text-align: right;">MAIN</div>
                        <div style="width: 55px; text-align: right;">TOTAL</div>
                    </div>
                    
                    @foreach($leftColumn as $index => $standing)
                        @php $isExpanded = in_array($index, $expandedDrivers); @endphp
                        <div 
                            wire:click="toggleDriver({{ $index }})"
                            data-driver-id="{{ $standing['entry']->car_number }}"
                            data-points="{{ $standing['total_points'] }}"
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
                                <span style="color: #22c55e; font-weight: bold;">#{{ $standing['entry']->car_number }}</span>
                                <span style="color: #fff;" class="truncate">{{ strtoupper($standing['entry']->driver_name) }}</span>
                            </div>
                            <div style="width: 70px; text-align: right; color: #666; font-size: 11px;">{{ $standing['qualifying_time'] ?? '-' }}</div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['qualifying_status'] ?? false ? '#ef4444' : '#888' }};">
                                {{ $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '' }}
                            </div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['heat_status'] ?? false ? '#ef4444' : '#888' }};">
                                {{ $standing['heat_status'] ?? $standing['heat_points'] ?? '' }}
                            </div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['amain_status'] ?? false ? '#ef4444' : '#888' }};">
                                {{ $standing['amain_status'] ?? $standing['amain_points'] ?? '' }}
                            </div>
                            <div style="width: 55px; text-align: right; color: #22c55e; font-weight: bold;">{{ $standing['total_points'] }}</div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Right Column --}}
                <div class="flex-1 flex flex-col" style="border-right: 1px solid #333;">
                    <div class="flex items-center px-3 py-1" style="background: #111; color: #666; border-bottom: 1px solid #333; font-size: 11px;">
                        <div style="width: 30px;">POS</div>
                        <div style="flex: 1;">DRIVER</div>
                        <div style="width: 70px; text-align: right;">TIME</div>
                        <div style="width: 45px; text-align: right;">QUAL</div>
                        <div style="width: 45px; text-align: right;">HEAT</div>
                        <div style="width: 45px; text-align: right;">MAIN</div>
                        <div style="width: 55px; text-align: right;">TOTAL</div>
                    </div>
                    
                    @foreach($rightColumn as $index => $standing)
                        @php 
                            $realIndex = $index + 20;
                            $isExpanded = in_array($realIndex, $expandedDrivers);
                        @endphp
                        <div 
                            wire:click="toggleDriver({{ $realIndex }})"
                            data-driver-id="{{ $standing['entry']->car_number }}"
                            data-points="{{ $standing['total_points'] }}"
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
                                <span style="color: #22c55e; font-weight: bold;">#{{ $standing['entry']->car_number }}</span>
                                <span style="color: #fff;" class="truncate">{{ strtoupper($standing['entry']->driver_name) }}</span>
                            </div>
                            <div style="width: 70px; text-align: right; color: #666; font-size: 11px;">{{ $standing['qualifying_time'] ?? '-' }}</div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['qualifying_status'] ?? false ? '#ef4444' : '#888' }};">
                                        {{ $standing['qualifying_status'] ?? $standing['qualifying_points'] ?? '' }}
                            </div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['heat_status'] ?? false ? '#ef4444' : '#888' }};">
                                        {{ $standing['heat_status'] ?? $standing['heat_points'] ?? '' }}
                            </div>
                            <div style="width: 45px; text-align: right; color: {{ $standing['amain_status'] ?? false ? '#ef4444' : '#888' }};">
                                        {{ $standing['amain_status'] ?? $standing['amain_points'] ?? '' }}
                            </div>
                            <div style="width: 55px; text-align: right; color: #22c55e; font-weight: bold;">{{ $standing['total_points'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Side Panel for Expanded Drivers --}}
            @if(count($expandedDrivers) > 0)
                <div class="flex flex-col overflow-y-auto" style="width: 320px; background: #0a0a0a; border-left: 1px solid #333;">
                    <div class="px-3 py-2" style="background: #111; border-bottom: 1px solid #333;">
                        <span style="color: #666; font-size: 11px;">POINTS BREAKDOWN</span>
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
                                            <span style="color: #22c55e; font-weight: bold;">#{{ $standing['entry']->car_number }}</span>
                                            <span style="color: #fff; font-weight: bold;">{{ strtoupper($standing['entry']->driver_name) }}</span>
                                        </div>
                                        <button 
                                            wire:click="toggleDriver({{ $driverIndex }})"
                                            style="color: #555; font-size: 16px;"
                                        >&times;</button>
                                    </div>
                                    
                                    {{-- Points summary --}}
                                    <div class="flex gap-4 mb-2 text-xs" style="color: #666;">
                                        <span>Qual: <span style="color: #888;">{{ $standing['qualifying_points'] ?? 0 }}</span></span>
                                        <span>Heat: <span style="color: #888;">{{ $standing['heat_points'] ?? 0 }}</span></span>
                                        <span>Main: <span style="color: #888;">{{ $standing['amain_points'] ?? 0 }}</span></span>
                                        <span>Total: <span style="color: #22c55e; font-weight: bold;">{{ $standing['total_points'] }}</span></span>
                                    </div>
                                    
                                    {{-- Results cards --}}
                                    <div class="flex flex-col gap-2">
                                                    @foreach($standing['all_results'] ?? [] as $result)
                                            <div style="background: #151515; border: 1px solid #2a2a2a; border-radius: 4px; padding: 6px 8px;">
                                                <div class="flex items-center justify-between" style="font-size: 11px;">
                                                    <div>
                                                        <span style="color: #22c55e;">{{ $result['session_name'] }}</span>
                                                        <span style="color: #444; margin-left: 4px;">{{ $result['session_type'] }}</span>
                                                    </div>
                                                    <span style="color: #22c55e; font-weight: bold;">{{ $result['points'] }} pts</span>
                                                            </div>
                                                <div class="flex items-center justify-between mt-1" style="font-size: 12px;">
                                                                    @if($result['is_dns'])
                                                        <span style="color: #ef4444;">DNS</span>
                                                                    @elseif($result['is_dnf'])
                                                        <span style="color: #ef4444;">DQ</span>
                                                                    @else
                                                        <span style="color: #888;">Position {{ $result['position'] }}</span>
                                                                    @endif
                                                            @if($result['time'])
                                                        <span style="color: #555;">{{ $result['time'] }}</span>
                                                            @endif
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
