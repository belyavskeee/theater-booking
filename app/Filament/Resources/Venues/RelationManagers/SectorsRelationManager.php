<?php

namespace App\Filament\Resources\Venues\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectorsRelationManager extends RelationManager
{
    protected static string $relationship = 'sectors';

    protected static ?string $title = 'Секторы зала';

    protected static ?string $modelLabel = 'сектор';

    protected static ?string $pluralModelLabel = 'секторы';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название сектора')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Например: Партер, Балкон, Ложа №1'),

                TextInput::make('rows_count')
                    ->label('Количество рядов')
                    ->required()
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->maxValue(100),

                TextInput::make('seats_per_row')
                    ->label('Мест в ряду')
                    ->required()
                    ->numeric()
                    ->default(12)
                    ->minValue(1)
                    ->maxValue(100),

                TextInput::make('price_modifier')
                    ->label('Коэффициент цены')
                    ->required()
                    ->numeric()
                    ->default(1.00)
                    ->step(0.01)
                    ->minValue(0.1)
                    ->maxValue(10.0)
                    ->helperText('Множитель к базовой цене. Например, 1.5 — сектор на 50% дороже.'),

                Hidden::make('sort_order')
                    ->default(fn () => (int) $this->getOwnerRecord()
                        ->sectors()
                        ->max('sort_order') + 1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('rows_count')
                    ->label('Рядов')
                    ->numeric()
                    ->badge()
                    ->color('info'),

                TextColumn::make('seats_per_row')
                    ->label('Мест в ряду')
                    ->numeric()
                    ->badge()
                    ->color('info'),

                TextColumn::make('total_capacity')
                    ->label('Всего мест')
                    ->state(fn ($record) => $record->rows_count * $record->seats_per_row)
                    ->badge()
                    ->color('success'),

                TextColumn::make('price_modifier')
                    ->label('Коэфф.')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('×')
                    ->badge()
                    ->color(fn ($state) => $state > 1 ? 'warning' : 'gray'),

                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()->label('Добавить сектор'),
            ])
            ->recordActions([
                EditAction::make()->label('Изменить'),
                DeleteAction::make()->label('Удалить'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}