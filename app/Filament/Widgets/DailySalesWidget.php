<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DailySalesWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $todaySalesAmount = Sale::whereDate('sold_at', $today)->sum('selling_price');
        $todayItemsSold   = Sale::whereDate('sold_at', $today)->count();

        $monthlySales = Sale::whereMonth('sold_at', now()->month)
            ->whereYear('sold_at', now()->year)
            ->sum('selling_price');

        return [
            Stat::make('Today Sales', '৳ ' . number_format($todaySalesAmount, 2))
                ->description('Total sold today')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Items Sold Today', $todayItemsSold)
                ->description('Total products sold')
                ->color('primary')
                ->icon('heroicon-o-shopping-bag'),

            // Stat::make('This Month Sales', '৳ ' . number_format($monthlySales, 2))
            //     ->description('Monthly total')
            //     ->color('warning')
            //     ->icon('heroicon-o-chart-bar'),
        ];
    }
}
