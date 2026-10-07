<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основное')
                    ->schema([
                        TextInput::make('name')
                            ->label('Имя')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Телефон')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+375 XX XXX-XX-XX'),

                        TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText('Оставьте пустым при редактировании, чтобы не менять.'),
                    ])
                    ->columns(2),

                Section::make('Роли и доступ')
                    ->schema([
                        Select::make('roles')
                            ->label('Роли')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Роль «admin» даёт полный доступ, «cashier» — только кассу.'),
                    ]),
            ]);
    }
}