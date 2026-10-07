<?php

namespace App\Filament\Resources\Spectacles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SpectacleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основное')
                    ->schema([
                        ImageEntry::make('poster_path')
                            ->label('Постер')
                            ->height(180),

                        TextEntry::make('title')
                            ->label('Название')
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('category.name')
                            ->label('Категория')
                            ->badge()
                            ->color('primary')
                            ->placeholder('—'),

                        TextEntry::make('slug')
                            ->label('Ссылка (URL)')
                            ->copyable()
                            ->color('gray')
                            ->placeholder('—'),
                    ])
                    ->columns(2),

                Section::make('Описание')
                    ->schema([
                        TextEntry::make('short_description')
                            ->label('Краткое описание')
                            ->placeholder('—')
                            ->columnSpanFull(),

                        TextEntry::make('description')
                            ->label('Полное описание')
                            ->html()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Параметры')
                    ->schema([
                        TextEntry::make('age_limit')
                            ->label('Возраст')
                            ->suffix('+')
                            ->badge()
                            ->color(fn ($state) => $state >= 18 ? 'danger' : ($state >= 12 ? 'warning' : 'success')),

                        TextEntry::make('duration_minutes')
                            ->label('Длительность')
                            ->suffix(' мин')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('intermission_minutes')
                            ->label('Антракт')
                            ->suffix(' мин')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('full_duration')
                            ->label('С антрактом')
                            ->state(fn ($record) => $record->full_duration)
                            ->suffix(' мин')
                            ->badge()
                            ->color('primary'),

                        IconEntry::make('is_active')
                            ->label('Активен')
                            ->boolean(),
                    ])
                    ->columns(5),

                Section::make('Постановка')
                    ->schema([
                        TextEntry::make('director')
                            ->label('Режиссёр')
                            ->placeholder('—'),

                        TextEntry::make('artist')
                            ->label('Художник')
                            ->placeholder('—'),

                        TextEntry::make('author')
                            ->label('Автор')
                            ->placeholder('—'),

                        TextEntry::make('trailer_url')
                            ->label('Трейлер')
                            ->url(fn ($state) => $state, true)
                            ->color('primary')
                            ->icon('heroicon-o-play-circle')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('В ролях')
                    ->schema([
                        TextEntry::make('cast')
                            ->hiddenLabel()
                            ->state(fn ($record) => collect($record->cast ?? [])
                                ->map(fn ($actor, $role) => "**{$role}** — {$actor}")
                                ->implode("\n"))
                            ->markdown()
                            ->placeholder('Роли не заполнены')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Статистика')
                    ->schema([
                        TextEntry::make('performances_count')
                            ->label('Всего показов')
                            ->state(fn ($record) => $record->performances()->count())
                            ->badge()
                            ->color('info'),

                        TextEntry::make('upcoming_count')
                            ->label('Ближайших')
                            ->state(fn ($record) => $record->upcomingPerformances()->count())
                            ->badge()
                            ->color('success'),

                        TextEntry::make('reviews_count')
                            ->label('Отзывов')
                            ->state(fn ($record) => $record->reviews()->count())
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('average_rating')
                            ->label('Средний рейтинг')
                            ->state(fn ($record) => $record->averageRating() ?: '—')
                            ->badge()
                            ->color('primary'),
                    ])
                    ->columns(4),

                Section::make('Служебное')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Создан')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('updated_at')
                            ->label('Обновлён')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}