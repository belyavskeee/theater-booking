<?php

namespace App\Filament\Resources\ReviewVotes;

use App\Filament\Resources\ReviewVotes\Pages\CreateReviewVote;
use App\Filament\Resources\ReviewVotes\Pages\EditReviewVote;
use App\Filament\Resources\ReviewVotes\Pages\ListReviewVotes;
use App\Filament\Resources\ReviewVotes\Schemas\ReviewVoteForm;
use App\Filament\Resources\ReviewVotes\Tables\ReviewVotesTable;
use App\Models\ReviewVote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewVoteResource extends Resource
{
    protected static ?string $model = ReviewVote::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ReviewVoteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewVotesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviewVotes::route('/'),
            'create' => CreateReviewVote::route('/create'),
            'edit' => EditReviewVote::route('/{record}/edit'),
        ];
    }
}
