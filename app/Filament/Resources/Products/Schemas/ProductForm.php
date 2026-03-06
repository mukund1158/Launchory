<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                TextInput::make('tagline')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('logo'),
                TextInput::make('website_url')
                    ->url()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('listing_type')
                    ->options(['launch' => 'Launch', 'directory' => 'Directory', 'both' => 'Both'])
                    ->default('both')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('pending')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                DatePicker::make('featured_until'),
                DatePicker::make('launch_date'),
                TextInput::make('vote_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_do_follow')
                    ->required(),
                TextInput::make('twitter_handle'),
                Textarea::make('maker_comment')
                    ->columnSpanFull(),
                Select::make('pricing')
                    ->options(['free' => 'Free', 'freemium' => 'Freemium', 'paid' => 'Paid'])
                    ->default('free')
                    ->required(),
            ]);
    }
}
