<?php

namespace App\Filament\Resources\ReviewVotes\Pages;

use App\Filament\Resources\ReviewVotes\ReviewVoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReviewVotes extends ListRecords
{
    protected static string $resource = ReviewVoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
