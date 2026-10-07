<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->placeholder('—'),

                TextColumn::make('roles.name')
                    ->label('Роли')
                    ->badge()
                    ->separator(',')
                    ->color(fn (string $state) => match ($state) {
                        'admin'   => 'danger',
                        'cashier' => 'warning',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'admin'   => 'Администратор',
                        'cashier' => 'Кассир',
                        default   => $state,
                    }),

                IconColumn::make('email_verified_at')
                    ->label('Email подтверждён')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->state(fn ($record) => $record->email_verified_at !== null)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('roles')
                    ->label('Роль')
                    ->options(fn () => Role::pluck('name', 'id')->toArray())
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $role = Role::find($data['value']);
                            if ($role) {
                                $query->whereHas('roles', fn ($q) => $q->where('name', $role->name));
                            }
                        }
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