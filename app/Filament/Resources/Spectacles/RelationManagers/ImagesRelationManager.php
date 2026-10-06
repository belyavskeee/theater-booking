<?php

namespace App\Filament\Resources\Spectacles\RelationManagers;

use App\Models\SpectacleImage;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Галерея';

    protected static ?string $modelLabel = 'изображение';

    protected static ?string $pluralModelLabel = 'изображения';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Изображение')
                    ->image()
                    ->directory('spectacles/gallery')
                    ->required(),

                Hidden::make('sort_order')
                    ->default(fn () => (int) SpectacleImage::max('sort_order') + 1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')
                    ->label('Превью')
                    ->height(80),

                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Добавить изображение'),
            ])
            ->recordActions([
                EditAction::make()->label('Изменить'),
                DeleteAction::make()->label('Удалить'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}