<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\Pages\AppViewRecord;
use App\Helpers\RoleHelper;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Carbon\CarbonInterface;

class ViewLoan extends AppViewRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        $isPending = $this->record->status === 'pendiente';

        return [
            Actions\Action::make('approve')
                ->label('Aprobar préstamo')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && $isPending)
                ->action(function () {
                    $this->record->update([
                        'status' => 'en_curso',
                        'issued_by' => $this->record->issued_by ?? Auth::id(),
                    ]);

                    Notification::make()
                        ->title('Préstamo aprobado')
                        ->body('Se actualizó el estado a "En curso" y quedó asignado a tu usuario.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('cancel')
                ->label($isPending ? 'Rechazar préstamo' : 'Cancelar préstamo')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']))
                ->form([
                    Textarea::make('reason')
                        ->label($isPending ? 'Motivo del rechazo' : 'Motivo de cancelación')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data) use ($isPending) {
                    $newStatus = $isPending ? 'rechazada' : 'cancelada';
                    $noteLabel = $isPending ? 'Motivo rechazo' : 'Motivo cancelación';
                    $notes = trim(($this->record->notes ?? '') . PHP_EOL . $noteLabel . ': ' . $data['reason']);

                    $this->record->update([
                        'status' => $newStatus,
                        'issued_by' => $this->record->issued_by ?? Auth::id(),
                        'notes' => $notes,
                    ]);

                    Notification::make()
                        ->title($isPending ? 'Préstamo rechazado' : 'Préstamo cancelado')
                        ->body($isPending ? 'El préstamo fue marcado como rechazado.' : 'El préstamo fue cancelado.')
                        ->danger()
                        ->send();
                }),
        ];
    }

    protected function getInfolists(): array
    {
        return [
            'default' => Infolist::make()
                ->schema([
                    Section::make(' Resumen del préstamo')
                        ->description('Información clave para decidir si se aprueba, cancela o ajusta el pedido.')
                        ->extraAttributes(['class' => 'bg-white shadow-sm rounded-2xl ring-1 ring-gray-100'])
                        ->schema([
                            Grid::make(4)
                                ->schema([
                                    TextEntry::make('loan_code')
                                        ->label('Código')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-hashtag', $state ? "Código {$state}" : 'Sin código'))
                                        ->extraAttributes(['class' => 'bg-slate-50 rounded-xl px-4 py-3 text-base font-semibold']),
                                    TextEntry::make('status')
                                        ->label('Estado')
                                        ->html()
                                        ->formatStateUsing(fn (?string $state) => $this->iconText('heroicon-o-sparkles', $this->humanStatus($state)))
                                        ->extraAttributes(['class' => 'bg-amber-50 rounded-xl px-4 py-3 text-base font-semibold flex items-center gap-2 justify-between']),
                                    TextEntry::make('borrower.name')
                                        ->label('Solicitante')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-user-circle', $state ?? 'Sin solicitante'))
                                        ->extraAttributes(['class' => 'bg-indigo-50 rounded-xl px-4 py-3 text-sm']),
                                    TextEntry::make('issuer.name')
                                        ->label('Asignado a')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-clipboard-document-check', $state ?? 'Sin asignar'))
                                        ->placeholder('Pendiente de asignar')
                                        ->extraAttributes(['class' => 'bg-emerald-50 rounded-xl px-4 py-3 text-sm']),
                                ]),
                            Grid::make(4)
                                ->schema([
                                    TextEntry::make('loan_at')
                                        ->label(' Entrega programada')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-calendar', $state ? $state->format('d/m/Y H:i') : 'Sin definir'))
                                        ->extraAttributes(['class' => 'bg-white/80 backdrop-blur rounded-xl px-4 py-3 border border-slate-100']),
                                    TextEntry::make('due_at')
                                        ->label(' Devolución programada')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-clock', $state ? $state->format('d/m/Y H:i') : 'Sin definir'))
                                        ->extraAttributes(['class' => 'bg-white/80 backdrop-blur rounded-xl px-4 py-3 border border-slate-100']),
                                    TextEntry::make('return_at')
                                        ->label('↩ Devuelto el')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-arrow-uturn-left', $state ? $state->format('d/m/Y H:i') : 'Pendiente'))
                                        ->extraAttributes(['class' => 'bg-white/80 backdrop-blur rounded-xl px-4 py-3 border border-slate-100']),
                                    TextEntry::make('duration')
                                        ->label(' Duración estimada')
                                        ->html()
                                        ->formatStateUsing(fn ($state, $record) => $this->iconText('heroicon-o-arrows-right-left', $record->loan_at && $record->due_at
                                            ? $record->loan_at->diffForHumans($record->due_at, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'parts' => 2])
                                            : 'Sin definir'))
                                        ->extraAttributes(['class' => 'bg-white/80 backdrop-blur rounded-xl px-4 py-3 border border-slate-100']),
                                ]),
                        ]),
                    Section::make(' Contacto y seguimiento')
                        ->description('Datos rápidos del solicitante para coordinar entrega o pedir soporte adicional.')
                        ->extraAttributes(['class' => 'bg-gradient-to-r from-slate-900 to-slate-700 text-white rounded-2xl shadow-lg'])
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextEntry::make('borrower.email')
                                        ->label(' Correo institucional')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-envelope', $state ?? 'Sin correo registrado'))
                                        ->copyable()
                                        ->copyableState(fn ($state) => $state)
                                        ->placeholder('Sin correo registrado')
                                        ->extraAttributes(['class' => 'px-4 py-3 rounded-xl bg-white/10 backdrop-blur text-sm']),
                                    TextEntry::make('borrower.phone')
                                        ->label(' Teléfono')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-phone', $state ?: 'Sin teléfono'))
                                        ->placeholder('Sin teléfono')
                                        ->extraAttributes(['class' => 'px-4 py-3 rounded-xl bg-white/10 backdrop-blur text-sm']),
                                    TextEntry::make('borrower.display_role_label')
                                        ->label('🎓 Rol en plataforma')
                                        ->html()
                                        ->formatStateUsing(fn ($state) => $this->iconText('heroicon-o-identification', $state ?? 'Sin rol asignado'))
                                        ->extraAttributes(['class' => 'px-4 py-3 rounded-xl bg-white/10 backdrop-blur text-sm font-semibold']),
                                ]),
                        ]),
                    Section::make(' Inventario comprometido')
                        ->description('Revisa qué materiales están comprometidos y si quedan unidades pendientes de retorno.')
                        ->extraAttributes(['class' => 'bg-white shadow-sm rounded-2xl ring-1 ring-gray-100'])
                        ->schema([
                            TextEntry::make('materials')
                                ->hiddenLabel()
                                ->html()
                                ->formatStateUsing(function ($state, $record) {
                                    return $record->materials->map(function ($material) {
                                        $pending = max($material->pivot->loan_qty - $material->pivot->returned_qty, 0);
                                        $badgeColor = $pending === 0 ? 'success' : 'warning';

                                        return sprintf(
                                            '<div class="flex items-center justify-between border rounded-lg px-3 py-2 mb-2 bg-slate-50">
                                                <div>
                                                    <p class="font-semibold text-sm text-slate-900"> %s</p>
                                                    <p class="text-xs text-slate-500">Solicitado: %d · Devuelto: %d</p>
                                                </div>
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-%s-100 text-%s-800">%s</span>
                                            </div>',
                                            e($material->name),
                                            $material->pivot->loan_qty,
                                            $material->pivot->returned_qty,
                                            $badgeColor,
                                            $badgeColor,
                                            $pending === 0 ? 'Completado' : $pending . ' pendiente(s)'
                                        );
                                    })->implode('');
                                }),
                        ])
                        ->collapsible(),
                    Section::make(' Notas internas')
                        ->schema([
                            TextEntry::make('notes')
                                ->hiddenLabel()
                                ->placeholder('Sin observaciones adicionales.')
                                ->markdown()
                                ->extraAttributes(['class' => 'bg-slate-50 rounded-xl px-4 py-3 text-sm text-slate-700']),
                        ])
                        ->collapsed()
                        ->extraAttributes(['class' => 'bg-white shadow-sm rounded-2xl ring-1 ring-gray-100']),
                ]),
        ];
    }
}
