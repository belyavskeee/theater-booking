<?php

namespace App\Filament\Resources\Seats\Tables;

use App\Models\Seat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class SeatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('venue.name')
                    ->label('Зал')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('sector.name')
                    ->label('Сектор')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('row_number')
                    ->label('Ряд')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('seat_number')
                    ->label('Место')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price_modifier')
                    ->label('Коэфф. цены')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('×')
                    ->badge()
                    ->color(fn ($state) => $state > 1 ? 'warning' : ($state < 1 ? 'success' : 'gray'))
                    ->sortable(),

                IconColumn::make('has_tickets')
                    ->label('Есть билеты')
                    ->state(fn ($record) => $record->tickets()->exists())
                    ->boolean()
                    ->trueIcon('heroicon-o-ticket')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
            ])
            ->defaultSort('venue_id')
            ->filters([
                Filter::make('location')
                    ->form([
                        Select::make('venue_id')
                            ->label('Зал')
                            ->options(\App\Models\Venue::pluck('name', 'id'))
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('sector_id', null) && $set('row_number', null)),

                        Select::make('sector_id')
                            ->label('Сектор')
                            ->options(fn (callable $get) => \App\Models\Sector::query()
                                ->when($get('venue_id'), fn ($q, $v) => $q->where('venue_id', $v))
                                ->orderBy('sort_order')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('row_number', null)),

                        Select::make('row_number')
                            ->label('Ряд')
                            ->options(fn (callable $get) => \App\Models\Seat::query()
                                ->when($get('venue_id'), fn ($q, $v) => $q->where('venue_id', $v))
                                ->when($get('sector_id'), fn ($q, $v) => $q->where('sector_id', $v))
                                ->distinct()
                                ->orderBy('row_number')
                                ->pluck('row_number', 'row_number')
                                ->toArray()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['venue_id'], fn ($q, $v) => $q->where('venue_id', $v))
                            ->when($data['sector_id'], fn ($q, $v) => $q->where('sector_id', $v))
                            ->when($data['row_number'], fn ($q, $v) => $q->where('row_number', $v));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['venue_id'] ?? null) {
                            $indicators[] = 'Зал: ' . \App\Models\Venue::find($data['venue_id'])?->name;
                        }
                        if ($data['sector_id'] ?? null) {
                            $indicators[] = 'Сектор: ' . \App\Models\Sector::find($data['sector_id'])?->name;
                        }
                        if ($data['row_number'] ?? null) {
                            $indicators[] = 'Ряд: ' . $data['row_number'];
                        }
                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make()->label('Изменить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}