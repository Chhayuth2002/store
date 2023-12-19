<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    use ExposesTableToWidgets;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('status', 'pending')),
            'processing' => Tab::make()->query(fn ($query) => $query->where('status', 'processing')),
            'completed' => Tab::make()->query(fn ($query) => $query->where('status', 'completed')),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}
