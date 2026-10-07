<x-filament-panels::page>
    <style>
        /* ========== LAYOUT ========== */
        .pos-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 1024px) {
            .pos-grid { grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); }
        }
        .pos-full { grid-column: 1 / -1; }

        /* ========== HEADER ========== */
        .pos-header {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 768px) {
            .pos-header { grid-template-columns: 110px minmax(0, 1fr) 240px; }
        }
        .pos-poster {
            width: 110px;
            aspect-ratio: 2 / 3;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .35);
        }
        .pos-poster-empty {
            width: 110px;
            aspect-ratio: 2 / 3;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(245, 158, 11, .15), rgba(245, 158, 11, .03));
            border: 1px dashed rgba(245, 158, 11, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(245, 158, 11, .6);
        }
        .pos-title {
            font-size: 22px;
            font-weight: 800;
            margin: 14px 0 10px;
            line-height: 1.2;
        }
        .pos-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ========== STATS CARD ========== */
        .pos-stats {
            padding: 18px;
            border-radius: 14px;
            border: 1px solid rgba(107, 114, 128, .2);
            background: rgba(107, 114, 128, .05);
        }
        .pos-stats-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .pos-stats-numbers {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
        }
        .pos-stats-percent {
            font-size: 32px;
            font-weight: 900;
            color: #f59e0b;
            line-height: 1;
        }
        .pos-stats-total {
            font-size: 12px;
            color: #6b7280;
        }
        .pos-progress {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(107, 114, 128, .2);
            overflow: hidden;
            margin: 10px 0 14px;
        }
        .pos-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            transition: width .3s ease;
        }
        .pos-stats-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
        }

        /* ========== STAGE ========== */
        .pos-stage-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .pos-stage {
            display: inline-block;
            padding: 12px 90px;
            background: linear-gradient(180deg, rgba(245, 158, 11, .18), rgba(245, 158, 11, .02));
            border: 2px solid rgba(245, 158, 11, .4);
            border-bottom: none;
            border-radius: 16px 16px 0 0;
            color: #f59e0b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 8px;
            text-transform: uppercase;
        }

        /* ========== SECTOR ========== */
        .pos-sector {
            margin-bottom: 24px;
            padding: 20px 16px 16px;
            border-radius: 16px;
            border: 1px solid rgba(107, 114, 128, .15);
            background: rgba(107, 114, 128, .03);
        }
        .pos-sector:last-child {
            margin-bottom: 0;
        }
        .pos-sector-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px dashed rgba(107, 114, 128, .2);
            flex-wrap: wrap;
        }
        .pos-sector-name {
            font-size: 15px;
            font-weight: 800;
            color: #f59e0b;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .pos-sector-count {
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(107, 114, 128, .15);
            color: #9ca3af;
        }
        .pos-sector-mod {
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(245, 158, 11, .15);
            color: #f59e0b;
        }

        /* ========== ROWS & SEATS ========== */
        .pos-rows {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .pos-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .pos-row-label {
            width: 24px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
        }
        .pos-row-seats {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .pos-seat {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: all .15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1;
            font-family: inherit;
        }
        .pos-seat-free {
            background: #10b981;
            color: #fff;
        }
        .pos-seat-free:hover {
            background: #059669;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, .45);
        }
        .pos-seat-selected {
            background: #f59e0b;
            color: #fff;
            transform: scale(1.12);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .4), 0 6px 14px rgba(245, 158, 11, .55);
        }
        .pos-seat-taken {
            background: rgba(107, 114, 128, .25);
            color: rgba(156, 163, 175, .6);
            cursor: not-allowed;
        }

        /* ========== LEGEND ========== */
        .pos-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid rgba(107, 114, 128, .2);
            justify-content: center;
        }
        .pos-legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #9ca3af;
        }
        .pos-legend-box {
            width: 26px;
            height: 26px;
            border-radius: 7px;
        }

        /* ========== CART ========== */
        .pos-cart-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
            max-height: 260px;
            overflow-y: auto;
        }
        .pos-cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(107, 114, 128, .08);
        }
        .pos-cart-num {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(245, 158, 11, .18);
            color: #f59e0b;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pos-cart-info {
            flex: 1;
            min-width: 0;
        }
        .pos-cart-row {
            font-size: 12px;
            color: #9ca3af;
        }
        .pos-cart-price {
            font-size: 15px;
            font-weight: 800;
            color: #e5e7eb;
        }
        .pos-cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0 4px;
            border-top: 2px solid rgba(245, 158, 11, .3);
        }
        .pos-cart-total-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .pos-cart-total-value {
            font-size: 28px;
            font-weight: 900;
            color: #f59e0b;
        }
        .pos-cart-total-value small {
            font-size: 14px;
            font-weight: 600;
            color: #9ca3af;
        }
        .pos-cart-empty {
            text-align: center;
            padding: 36px 16px;
            color: #6b7280;
            font-size: 13px;
        }
        .pos-cart-empty strong {
            display: block;
            margin-top: 4px;
            color: #9ca3af;
            font-weight: 600;
        }
        .pos-empty {
            text-align: center;
            padding: 48px 24px;
            color: #6b7280;
        }
        .pos-empty-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            opacity: .3;
        }
    </style>

    @php
        $performance = $this->performance;
        $sectors = $this->seatsBySector;
    @endphp

    <div class="pos-grid">

        {{-- ============ ШАПКА ============ --}}
        <div class="pos-full">
            <x-filament::section>
                <div class="pos-header">
                    {{-- Постер --}}
                    <div>
                        @if ($performance && $performance->spectacle && $performance->spectacle->poster_path)
                            <img
                                src="{{ asset('storage/' . $performance->spectacle->poster_path) }}"
                                alt="{{ $performance->spectacle->title }}"
                                class="pos-poster"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="pos-poster-empty" style="display:none;">
                                <x-filament::icon icon="heroicon-o-photo" class="w-8 h-8" />
                            </div>
                        @else
                            <div class="pos-poster-empty">
                                <x-filament::icon icon="heroicon-o-photo" class="w-8 h-8" />
                            </div>
                        @endif
                    </div>

                    {{-- Информация --}}
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                            Выберите показ
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="performanceId">
                                <option value="">— выберите показ —</option>
                                @foreach ($this->performances as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->starts_at->format('d.m.Y H:i') }} — {{ $p->spectacle->title }} ({{ $p->venue->name }})
                                    </option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>

                        @if ($performance)
                            <h1 class="pos-title">{{ $performance->spectacle->title }}</h1>

                            <div class="pos-badges">
                                <x-filament::badge color="info" icon="heroicon-m-building-office-2">
                                    {{ $performance->venue->name }}
                                </x-filament::badge>
                                <x-filament::badge color="success" icon="heroicon-m-calendar-days">
                                    {{ $performance->starts_at->format('d.m.Y') }}
                                </x-filament::badge>
                                <x-filament::badge color="success" icon="heroicon-m-clock">
                                    {{ $performance->starts_at->format('H:i') }}
                                </x-filament::badge>
                                <x-filament::badge color="warning" icon="heroicon-m-banknotes">
                                    от {{ number_format($performance->base_price, 2) }} BYN
                                </x-filament::badge>
                                @if ($performance->spectacle->age_limit > 0)
                                    <x-filament::badge color="danger">
                                        {{ $performance->spectacle->age_limit }}+
                                    </x-filament::badge>
                                @endif
                                <x-filament::badge color="gray" icon="heroicon-m-clock">
                                    {{ $performance->spectacle->duration_minutes }} мин
                                </x-filament::badge>
                            </div>
                        @else
                            <p style="color: #6b7280; margin-top: 16px; font-size: 14px;">
                                Выберите показ из списка выше, чтобы начать продажу.
                            </p>
                        @endif
                    </div>

                    {{-- Статистика --}}
                    @if ($performance)
                        <div class="pos-stats">
                            <div class="pos-stats-title">
                                <x-filament::icon icon="heroicon-o-chart-pie" class="w-4 h-4" style="color:#f59e0b" />
                                Заполненность
                            </div>

                            <div class="pos-stats-numbers">
                                <div class="pos-stats-percent">{{ $this->hallStats['percent'] }}%</div>
                                <div class="pos-stats-total">
                                    {{ $this->hallStats['taken'] }} / {{ $this->hallStats['total'] }}
                                </div>
                            </div>

                            <div class="pos-progress">
                                <div class="pos-progress-bar" style="width: {{ $this->hallStats['percent'] }}%"></div>
                            </div>

                            <div class="pos-stats-row">
                                <span style="color: #10b981;">Свободно: {{ $this->hallStats['available'] }}</span>
                                <span style="color: #6b7280;">Продано: {{ $this->hallStats['taken'] }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- ============ КАРТА ЗАЛА ============ --}}
        <div>
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <x-filament::icon icon="heroicon-o-map" class="w-5 h-5" style="color: #f59e0b" />
                        Карта зала
                    </div>
                </x-slot>

                @if ($performance)
                    @if (empty($sectors))
                        <div class="pos-empty">
                            <x-filament::icon icon="heroicon-o-ticket" class="pos-empty-icon" />
                            <p>В зале нет мест.</p>
                            <p style="font-size: 12px; margin-top: 6px;">Добавьте секторы в настройках зала.</p>
                        </div>
                    @else
                        <div style="overflow-x: auto; padding: 8px 0 16px;">
                            <div style="min-width: max-content; margin: 0 auto;">
                                {{-- Сцена --}}
                                <div class="pos-stage-wrap">
                                    <div class="pos-stage">Сцена</div>
                                </div>

                                {{-- Секторы --}}
                                @foreach ($sectors as $sector)
                                    <div class="pos-sector" wire:key="sector-{{ $loop->index }}">
                                        {{-- Заголовок сектора --}}
                                        <div class="pos-sector-header">
                                            <span class="pos-sector-name">{{ $sector['name'] }}</span>
                                            <span class="pos-sector-count">{{ $sector['count'] }} мест</span>
                                            @if ($sector['modifier'] != 1.0)
                                                <span class="pos-sector-mod">
                                                    ×{{ number_format($sector['modifier'], 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Ряды сектора --}}
                                        <div class="pos-rows">
                                            @foreach ($sector['rows'] as $rowNumber => $seats)
                                                <div class="pos-row" wire:key="sector-{{ $loop->parent->index }}-row-{{ $rowNumber }}">
                                                    <span class="pos-row-label">{{ $rowNumber }}</span>
                                                    <div class="pos-row-seats">
                                                        @foreach ($seats as $seat)
                                                            @php $selected = isset($selectedSeats[$seat['id']]); @endphp
                                                            <button
                                                                type="button"
                                                                wire:key="seat-{{ $seat['id'] }}"
                                                                @disabled($seat['taken'])
                                                                wire:click="toggleSeat({{ $seat['id'] }}, {{ $seat['price'] }})"
                                                                title="Ряд {{ $rowNumber }}, место {{ $seat['number'] }} · {{ $sector['name'] }} · {{ number_format($seat['price'], 2) }} BYN"
                                                                class="pos-seat {{ $seat['taken'] ? 'pos-seat-taken' : ($selected ? 'pos-seat-selected' : 'pos-seat-free') }}"
                                                            >
                                                                {{ $seat['number'] }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Легенда --}}
                                <div class="pos-legend">
                                    <div class="pos-legend-item">
                                        <span class="pos-legend-box" style="background: #10b981;"></span>
                                        Свободно
                                    </div>
                                    <div class="pos-legend-item">
                                        <span class="pos-legend-box" style="background: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, .4);"></span>
                                        Выбрано
                                    </div>
                                    <div class="pos-legend-item">
                                        <span class="pos-legend-box" style="background: rgba(107, 114, 128, .3);"></span>
                                        Занято
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="pos-empty">
                        <x-filament::icon icon="heroicon-o-ticket" class="pos-empty-icon" />
                        <p>Выберите показ, чтобы увидеть карту зала</p>
                    </div>
                @endif
            </x-filament::section>
        </div>

        {{-- ============ КОРЗИНА ============ --}}
        <div>
            <div style="position: sticky; top: 24px; display: flex; flex-direction: column; gap: 16px;">
                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <x-filament::icon icon="heroicon-o-shopping-bag" class="w-5 h-5" style="color: #f59e0b" />
                                Корзина
                            </div>
                            @if ($this->selectedSeatsCount > 0)
                                <span style="padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 800; background: #f59e0b; color: #fff;">
                                    {{ $this->selectedSeatsCount }}
                                </span>
                            @endif
                        </div>
                    </x-slot>

                    @if (empty($selectedSeats))
                        <div class="pos-cart-empty">
                            <x-filament::icon icon="heroicon-o-inbox" class="w-10 h-10 mx-auto mb-2" style="opacity: .35" />
                            Места не выбраны
                            <strong>Кликните по креслу на карте зала</strong>
                        </div>
                    @else
                        <div class="pos-cart-list">
                            @foreach ($this->cartDetails as $item)
                                <div class="pos-cart-item" wire:key="cart-{{ $item['id'] }}">
                                    <div class="pos-cart-num">{{ $item['number'] }}</div>
                                    <div class="pos-cart-info">
                                        <div class="pos-cart-row">Ряд {{ $item['row'] }} · {{ $item['sector'] }}</div>
                                    </div>
                                    <div class="pos-cart-price">{{ number_format($item['price'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pos-cart-total">
                            <span class="pos-cart-total-label">Итого</span>
                            <span class="pos-cart-total-value">
                                {{ number_format($this->total, 2) }} <small>BYN</small>
                            </span>
                        </div>
                    @endif
                </x-filament::section>

                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <x-filament::icon icon="heroicon-o-user" class="w-5 h-5" style="color: #f59e0b" />
                            Покупатель
                        </div>
                    </x-slot>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Имя *</label>
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="customerName" placeholder="Иван Иванов" />
                            </x-filament::input.wrapper>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Телефон</label>
                            <x-filament::input.wrapper>
                                <x-filament::input type="tel" wire:model="customerPhone" placeholder="+375 XX XXX-XX-XX" />
                            </x-filament::input.wrapper>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Email</label>
                            <x-filament::input.wrapper>
                                <x-filament::input type="email" wire:model="customerEmail" placeholder="client@example.com" />
                            </x-filament::input.wrapper>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">Способ оплаты</label>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model="paymentMethod">
                                    <option value="cash">Наличные</option>
                                    <option value="card">Карта</option>
                                    <option value="erip">ЕРИП</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <x-filament::button
                            wire:click="checkout"
                            color="success"
                            icon="heroicon-o-check-circle"
                            size="lg"
                            style="width: 100%;"
                        >
                            Оформить продажу
                        </x-filament::button>

                        @if ($this->selectedSeatsCount > 0)
                            <x-filament::button
                                wire:click="clearCart"
                                color="gray"
                                icon="heroicon-o-x-mark"
                                style="width: 100%;"
                            >
                                Сбросить выбор
                            </x-filament::button>
                        @endif
                    </div>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>