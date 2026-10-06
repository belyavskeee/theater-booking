<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('spectacle.title')
                    ->label('Спектакль')
                    ->searchable()
                    ->wrap()
                    ->limit(30),

                TextColumn::make('rating')
                    ->label('Оценка')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color(fn (int $state) => match (true) {
                        $state >= 5 => 'success',
                        $state >= 3 => 'warning',
                        default     => 'danger',
                    }),

                TextColumn::make('text')
                    ->label('Отзыв')
                    ->wrap()
                    ->limit(60)
                    ->tooltip(fn ($state) => $state)
                    ->searchable(),

                TextColumn::make('votes_count')
                    ->label('Голосов')
                    ->counts('votes')
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_published')
                    ->label('Опубликован')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Написан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Статус публикации')
                    ->options([
                        1 => 'Опубликованные',
                        0 => 'Скрытые',
                    ]),

                SelectFilter::make('rating')
                    ->label('Оценка')
                    ->options([
                        5 => '5 звёзд',
                        4 => '4 звезды',
                        3 => '3 звезды',
                        2 => '2 звезды',
                        1 => '1 звезда',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label('Просмотр'),

                EditAction::make()->label('Изменить'),

                Action::make('togglePublish')
                    ->label(fn ($record) => $record->is_published ? 'Скрыть' : 'Опубликовать')
                    ->icon(fn ($record) => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->is_published ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_published' => !$record->is_published]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}