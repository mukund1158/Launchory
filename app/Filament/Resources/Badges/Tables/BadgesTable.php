<?php

namespace App\Filament\Resources\Badges\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BadgesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rank')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gold' => '🥇 Gold',
                        'silver' => '🥈 Silver',
                        'bronze' => '🥉 Bronze',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'gold' => 'warning',
                        'silver' => 'gray',
                        'bronze' => 'danger',
                    }),
                TextColumn::make('launch_date')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('launch_date', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
