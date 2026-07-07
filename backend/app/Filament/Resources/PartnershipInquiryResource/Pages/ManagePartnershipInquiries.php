<?php

namespace App\Filament\Resources\PartnershipInquiryResource\Pages;

use App\Filament\Resources\PartnershipInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePartnershipInquiries extends ManageRecords
{
    protected static string $resource = PartnershipInquiryResource::class;

    protected function getHeaderActions(): array
    {
        // Inquiries are submitted by visitors via the public partnership form.
        return [];
    }
}
