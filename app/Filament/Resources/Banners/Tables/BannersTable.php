<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Превью')
                    ->height(60)
                    ->width(120),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->limit(40),

                TextColumn::make('subtitle')
                    ->label('Подзаголовок')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('button_text')
                    ->label('Текст кнопки')
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                TextColumn::make('button_url')
                    ->label('Ссылка кнопки')
                    ->copyable()
                    ->limit(30)
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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