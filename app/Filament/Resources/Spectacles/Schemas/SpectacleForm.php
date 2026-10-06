<?php

namespace App\Filament\Resources\Spectacles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SpectacleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        // Автозаполнение slug только при создании записи
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Ссылка (URL)')
                    ->helperText('Автоматически заполняется из названия. Оставьте пустым для генерации.')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Textarea::make('short_description')
                    ->label('Краткое описание')
                    ->maxLength(255)
                    ->columnSpanFull(),

                RichEditor::make('description')
                    ->label('Полное описание')
                    ->columnSpanFull(),

                TextInput::make('age_limit')
                    ->label('Возврастное ограничение')
                    ->numeric()
                    ->default(0)                 // ← предзаполняем 0
                    ->required()                 // ← запрещаем пустое значение
                    ->minValue(0)
                    ->maxValue(18)
                    ->suffix('+'),

                TextInput::make('duration_minutes')
                    ->label('Длительность (мин)')
                    ->numeric()
                    ->default(60)                // ← по умолчанию 60
                    ->required()
                    ->minValue(1),

                TextInput::make('intermission_minutes')
                    ->label('Антракт (мин)')
                    ->numeric()
                    ->default(0)                 // ← по умолчанию 0
                    ->required()
                    ->minValue(0),

                TextInput::make('director')->label('Режиссёр'),
                TextInput::make('artist')->label('Художник'),
                TextInput::make('author')->label('Автор'),

                KeyValue::make('cast')
                    ->label('В ролях')
                    ->keyLabel('Роль')
                    ->valueLabel('Актёр'),

                FileUpload::make('poster_path')
                    ->label('Постер')
                    ->image()
                    ->directory('spectacles/posters'),

                TextInput::make('trailer_url')
                    ->label('Ссылка на трейлер')
                    ->url(),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ]);
    }
}