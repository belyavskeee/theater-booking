<?php

namespace App\Filament\Resources\ReviewVotes\Pages;

use App\Filament\Resources\ReviewVotes\ReviewVoteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReviewVote extends EditRecord
{
    protected static string $resource = ReviewVoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
