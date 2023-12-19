<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StateOverviewWidget extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?int $sort = 0;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }


    protected function getStats(): array
    {
        $orderData = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();


        return [
            Stat::make('Revenue this month', number_format($this->getPageTableQuery()->where('status', 'completed')->sum('total_price')))
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($orderData
                    ->map(fn (TrendValue $value) => $value->aggregate)
                    ->toArray())
                ->color('success'),
            Stat::make('New customers', '1340')
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('New orders', '3543')
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
