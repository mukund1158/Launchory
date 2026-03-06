<?php

namespace App\Filament\Resources\Badges\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BadgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('rank')
                    ->options(['gold' => 'Gold', 'silver' => 'Silver', 'bronze' => 'Bronze'])
                    ->required(),
                DatePicker::make('launch_date')
                    ->required(),
            ]);
    }
}
