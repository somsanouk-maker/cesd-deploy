<?php

namespace App\Filament\Resources\ServiceRequestResource\Pages;

use App\Filament\Resources\ServiceRequestResource;
use App\Models\ServiceRequest;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditServiceRequest extends EditRecord
{
    protected static string $resource = ServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_quotation')
                ->label('Send Quotation')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning')
                ->form([
                    TextInput::make('amount')
                        ->label('Amount (LAK)')
                        ->numeric()
                        ->required()
                        ->minValue(0),
                    Textarea::make('notes')->label('Notes')->rows(3),
                ])
                ->action(function (ServiceRequest $record, array $data) {
                    $record->setQuotation((float) $data['amount'], $data['notes'] ?? null, auth()->user());

                    Notification::make()
                        ->title('Quotation sent to '.$record->contact_email)
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * Route status changes through ServiceRequest::updateStatus() so they get
     * logged (service_request_status_logs) and trigger the customer email,
     * instead of silently writing the column via plain mass-update.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $newStatus = $data['status'] ?? $record->status;
        unset($data['status']);

        $record->update($data);

        if ($newStatus !== $record->status) {
            $record->updateStatus($newStatus, auth()->user());
        }

        return $record;
    }
}
