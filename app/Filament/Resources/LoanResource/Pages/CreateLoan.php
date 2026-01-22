<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\Pages\AppCreateRecord;
use App\Models\MaterialRequest;

class CreateLoan extends AppCreateRecord
{
    protected static string $resource = LoanResource::class;

    protected ?MaterialRequest $materialRequest = null;

    public function mount(): void
    {
        parent::mount();

        $this->materialRequest = $this->resolveMaterialRequest();

        if ($this->materialRequest) {
            $prefill = array_replace_recursive(
                (array) $this->form->getState(),
                $this->getPrefillDataFromMaterialRequest()
            );

            $this->form->fill($prefill);
        }
    }

    protected function getRedirectUrl(): string
    {
        return LoanResource::getUrl('index');
    }

    protected function resolveMaterialRequest(): ?MaterialRequest
    {
        $requestId = request()->query('material_request');

        if (!$requestId) {
            return null;
        }

        return MaterialRequest::with(['material', 'requester'])->find($requestId);
    }

    protected function getPrefillDataFromMaterialRequest(): array
    {
        if (!$this->materialRequest || !$this->materialRequest->material || !$this->materialRequest->requester) {
            return [];
        }

        return [
            'user_id' => $this->materialRequest->user_id,
            'loan_at' => $this->materialRequest->needed_at,
            'due_at' => $this->materialRequest->planned_return_at,
            'notes' => trim(sprintf(
                'Solicitud #%d: %s | Necesita %s unidad(es) para %s. %s',
                $this->materialRequest->id,
                $this->materialRequest->material->name,
                $this->materialRequest->quantity,
                optional($this->materialRequest->needed_at)->format('d/m/Y H:i'),
                $this->materialRequest->notes ?? 'Sin notas adicionales.'
            )),
            'materials' => [[
                'material_id' => $this->materialRequest->material_id,
                'loan_qty' => $this->materialRequest->quantity,
                'returned_qty' => 0,
                'is_returned' => false,
            ]],
        ];
    }
}
