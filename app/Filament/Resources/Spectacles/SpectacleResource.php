<?php

namespace App\Filament\Resources\Spectacles;

use App\Filament\Resources\Spectacles\Pages\CreateSpectacle;
use App\Filament\Resources\Spectacles\Pages\EditSpectacle;
use App\Filament\Resources\Spectacles\Pages\ListSpectacles;
use App\Filament\Resources\Spectacles\Pages\ViewSpectacle;
use App\Filament\Resources\Spectacles\Schemas\SpectacleForm;
use App\Filament\Resources\Spectacles\Schemas\SpectacleInfolist;
use App\Filament\Resources\Spectacles\Tables\SpectaclesTable;
use App\Models\Spectacle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SpectacleResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Контент';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-film';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Спектакль';
    protected static ?string $pluralModelLabel = 'Спектакли';
    protected static ?string $navigationLabel = 'Спектакли';

    protected static ?string $model = Spectacle::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SpectacleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SpectacleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpectaclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Spectacles\RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSpectacles::route('/'),
            'create' => CreateSpectacle::route('/create'),
            'view'   => ViewSpectacle::route('/{record}'),
            'edit'   => EditSpectacle::route('/{record}/edit'),
        ];
    }
}