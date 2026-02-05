<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Filament\Resources\CertificateResource\RelationManagers;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'گواهینامه‌ها';

    protected static ?string $pluralModelLabel = 'گواهینامه‌ها';

    protected static ?string $modelLabel = 'گواهینامه';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'مدیریت گواهینامه';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('viewAny', \App\Models\Certificate::class) ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        return Certificate::query()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';

    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo(auth()->user());
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->label(__('fields.event_id'))
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->required(),

                Select::make('certificate_holder_id')
                    ->label(__('fields.certificate_holder_id'))
                    ->relationship(
                        'certificateHolder',
                        'first_name',
                        fn (Builder $query) => $query
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => $record->full_name
                    )
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->required(),

                Select::make('status')
                    ->label(__('fields.status'))
                    ->options([
                        'draft'   => __('fields.certificate_statuses.draft'),
                        'active'  => __('fields.certificate_statuses.active'),
                        'revoked' => __('fields.certificate_statuses.revoked'),
                    ])
                    ->required()
                    ->default('active'),

                Section::make('اطلاعات سیستمی')
                    ->columns(2)
                    ->visible(fn ($record) => $record !== null)
                    ->schema([

                        Placeholder::make('views')
                            ->label(__('fields.views'))
                            ->content(fn ($record) =>
                            number_format($record->views)
                            ),

                        Placeholder::make('serial')
                            ->label(__('fields.serial'))
                            ->content(fn ($record) =>
                            $record->serial
                            ),

                        Placeholder::make('public_link')
                            ->label(__('fields.certificate_show_link'))
                            ->content(fn ($record) =>
                            new HtmlString(
                                '<a href="' .
                                route('certificates.show', $record->serial) .
                                '" target="_blank" class="text-primary-600 underline">
                        مشاهده گواهینامه
                    </a>'
                            )
                            ),

                    ])
                ,

        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                TextColumn::make('event.title')
                    ->label(__('fields.event_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('certificateHolder.full_name')
                    ->label(__('fields.certificate_holder_id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('serial')
                    ->label(__('fields.serial'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('شماره سریال کپی شد')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('public_link')
                    ->label(__('fields.link'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->state(fn () => true)
                    ->tooltip('مشاهده گواهینامه')
                    ->alignCenter()
                    ->url(fn ($record) => route('certificates.show', $record->serial))
                    ->openUrlInNewTab()
                    ->color('primary'),

                TextColumn::make('issued_at')
                    ->label(__('fields.issued_at'))
                    ->formatStateUsing(fn ($state, $record) => $record->jalali['issued_at'])
                    ->sortable(),

                Tables\Columns\ViewColumn::make('status_and_payment')
                    ->label(__('fields.status'))
                    ->view('filament.columns.status_and_payment')
                    ->getStateUsing(fn ($record) => [
                        'status' => $record->status,
                        'has_payment_issue' => $record->has_payment_issue,
                    ]),

            ])

            ->actions([
                Tables\Actions\Action::make('payFromWallet')
                    ->label('')
                    ->tooltip(__('fields.pay_with_wallet'))
                    ->icon('heroicon-o-wallet')
                    ->color('success')
                    ->visible(fn ($record) => $record?->has_payment_issue === 1)
                    ->requiresConfirmation()
                    ->modalHeading(__('fields.pay_with_wallet'))
                    ->modalDescription(__('fields.confirm_wallet_payment_description'))
                    ->modalSubmitActionLabel(__('fields.pay_with_wallet'))
                    ->action(fn ($record) => self::pay($record)),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف گروهی'),
                ]),
            ]);
    }

    protected static function pay($record): void
    {
        try {
            app(\App\Services\Payments\WalletPaymentService::class)
                ->payForCertificate(
                    certificate: $record,
                    payerUserId: auth()->id()
                );

            Notification::make()
                ->title(__('fields.wallet_payment_success'))
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title(__('fields.wallet_payment_failed'))
                ->danger()
                ->send();

            report($e);
        }
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
