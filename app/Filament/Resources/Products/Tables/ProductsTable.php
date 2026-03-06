<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Badge;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record->logo_url),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Maker')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                TextColumn::make('listing_type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('vote_count')
                    ->label('Votes')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('listing_type')
                    ->options([
                        'launch' => 'Launch',
                        'directory' => 'Directory',
                        'both' => 'Both',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'approved')
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
                Action::make('feature')
                    ->label('Mark Featured')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !$record->is_featured)
                    ->action(fn ($record) => $record->update([
                        'is_featured' => true,
                        'featured_until' => now()->addDays(30),
                    ])),
                Action::make('assign_badge')
                    ->label('Assign Badge')
                    ->icon('heroicon-o-trophy')
                    ->color('warning')
                    ->form([
                        Select::make('rank')
                            ->options([
                                'gold' => '🥇 Gold',
                                'silver' => '🥈 Silver',
                                'bronze' => '🥉 Bronze',
                            ])
                            ->required(),
                    ])
                    ->visible(fn ($record) => !$record->badge)
                    ->action(fn ($record, array $data) => Badge::create([
                        'product_id' => $record->id,
                        'rank' => $data['rank'],
                        'launch_date' => now()->toDateString(),
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
