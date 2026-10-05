<?php

namespace App\Filament\Resources\ReviewVotes\Pages;

use App\Filament\Resources\ReviewVotes\ReviewVoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReviewVote extends CreateRecord
{
    protected static string $resource = ReviewVoteResource::class;
}
