<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;
    protected static string $resource = OrderResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Order Details')
                ->schema([
                    Section::make()->schema(OrderResource::getFormSchema())->columns(),
                ]),

            Step::make('Order Items')
                ->schema([
                    Section::make()->schema(OrderResource::getFormSchema('orderItems')),
                ]),
        ];
    }
}
