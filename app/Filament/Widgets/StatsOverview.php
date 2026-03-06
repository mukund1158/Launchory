<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Vote;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pendingCount = Product::where('status', 'pending')->count();

        return [
            Stat::make('Approved Products', Product::where('status', 'approved')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users'),
            Stat::make("Today's Votes", Vote::whereDate('created_at', today())->count())
                ->icon('heroicon-o-hand-thumb-up'),
            Stat::make('Pending Review', $pendingCount)
                ->icon('heroicon-o-clock')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->description($pendingCount > 0 ? "{$pendingCount} products awaiting review" : 'All caught up!'),
        ];
    }
}
