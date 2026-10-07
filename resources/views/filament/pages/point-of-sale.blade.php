<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Выбор показа --}}
        <div class="lg:col-span-3">
            <x-filament::section heading="Выберите показ">
                <select
                    wire:change="selectPerformance($event.target.value)"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">— выберите —</option>
                    @foreach ($this->performances as $p)
                        <option value="{{ $p->id }}" @selected($performanceId === $p->id)>
                            {{ $p->starts_at->format('d.m.Y H:i') }} —
                            {{ $p->spectacle->title }} ({{ $p->venue->name }})
                        </option>
                    @endforeach
                </select>
            </x-filament::section>
        </div>

        {{-- Карта зала --}}
        <div class="lg:col-span-2">
            <x-filament::section heading="Карта зала" wire:poll.5s>
                @if ($this->performance)
                    <div class="space-y-2">
                        @foreach ($this->seatsByRow as $rowNumber => $seats)
                            <div class="flex items-center gap-2">
                                <span class="w-10 text-xs text-gray-500">Ряд {{ $rowNumber }}</span>
                                <div class="flex gap-1 flex-wrap">
                                    @foreach ($seats as $seat)
                                        @php
                                            $selected = isset($selectedSeats[$seat['id']]);
                                        @endphp
                                        <button
                                            type="button"
                                            @disabled($seat['taken'])
                                            wire:click="toggleSeat({{ $seat['id'] }}, {{ $seat['price'] }})"
                                            title="Ряд {{ $rowNumber }}, место {{ $seat['number'] }} — {{ $seat['price'] }} BYN"
                                            class="w-9 h-9 text-xs rounded
                                                {{ $seat['taken']
                                                    ? 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed'
                                                    : ($selected
                                                        ? 'bg-primary-500 text-white'
                                                        : 'bg-success-500 hover:bg-success-600 text-white') }}"
                                        >
                                            {{ $seat['number'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Выберите показ.</p>
                @endif
            </x-filament::section>
        </div>

        {{-- Корзина --}}
        <div>
            <x-filament::section heading="Корзина">
                @if (empty($selectedSeats))
                    <p class="text-sm text-gray-500">Места не выбраны.</p>
                @else
                    <ul class="space-y-1 text-sm mb-3">
                        @foreach ($selectedSeats as $id => $price)
                            <li class="flex justify-between">
                                <span>Место #{{ $id }}</span>
                                <span>{{ $price }} BYN</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-lg font-bold mb-4">
                        Итого: {{ $this->total }} BYN
                    </p>
                @endif

                <div class="space-y-2">
                    <input wire:model="customerName" placeholder="Имя покупателя"
                        class="w-full rounded-lg border-gray-300 text-sm">
                    <input wire:model="customerPhone" placeholder="Телефон"
                        class="w-full rounded-lg border-gray-300 text-sm">
                    <input wire:model="customerEmail" placeholder="Email (необязательно)"
                        class="w-full rounded-lg border-gray-300 text-sm">

                    <select wire:model="paymentMethod"
                        class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="cash">Наличные</option>
                        <option value="card">Карта</option>
                        <option value="erip">ЕРИП</option>
                    </select>
                </div>

                <div class="mt-4 flex gap-2">
                    <x-filament::button wire:click="checkout" color="success" class="flex-1">
                        Продать
                    </x-filament::button>
                    <x-filament::button wire:click="clearCart" color="gray">
                        Сброс
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>