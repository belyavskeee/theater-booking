<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('performance_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled', 'expired' => 'Expired'])
                    ->default('pending')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                DateTimePicker::make('expires_at'),
                TextInput::make('customer_name'),
                TextInput::make('customer_email')
                    ->email(),
                TextInput::make('customer_phone')
                    ->tel(),
                TextInput::make('payment_method'),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
