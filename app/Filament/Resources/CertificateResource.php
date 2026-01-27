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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

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
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label(__('fields.event_id'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('certificateHolder.full_name')
                    ->label(__('fields.certificate_holder_id'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('serial')
                    ->label(__('fields.serial'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('شماره سریال کپی شد')
                    ->copyMessageDuration(1500),

                IconColumn::make('public_link')
                    ->label(__('fields.link'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->state(fn () => true)
                    ->tooltip('مشاهده گواهینامه')
                    ->alignCenter()
                    ->url(fn ($record) =>
                    route('certificates.show', $record->serial)
                    )
                    ->openUrlInNewTab()
                    ->color('primary')
                ,

                Tables\Columns\TextColumn::make('jalali.issued_at')
                    ->label(__('fields.issued_at'))
//                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('fields.status'))
                    ->formatStateUsing(fn (?string $state) => __("fields.certificate_statuses.$state")),

                Tables\Columns\IconColumn::make('has_payment_issue')
                    ->label(__('fields.has_payment_issue'))
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('ویرایش')
                    ->icon('heroicon-o-pencil-square')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف گروهی'),
                ]),
            ])
            ->contentGrid([
                'md' =>2,
                'xl' => 3
            ]);
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
