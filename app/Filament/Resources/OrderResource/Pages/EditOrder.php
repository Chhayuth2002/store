<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print Invoice')->button()
                ->action(fn () => OrderResource::printInvoice($this->record)),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['ach_transfer'] == false) {
            $data['ach_account_number'] = null;
            $data['ach_routing_number'] = null;
            $data['ach_account_address'] = null;
        }

        return $data;
    }
}
