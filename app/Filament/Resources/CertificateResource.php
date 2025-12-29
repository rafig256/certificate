<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Filament\Resources\CertificateResource\RelationManagers;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'گواهینامه‌ها';

    protected static ?string $pluralModelLabel = 'گواهینامه‌ها';

    protected static ?string $modelLabel = 'گواهینامه';

    protected static ?string $navigationGroup = 'مدیریت گواهینامه‌ها';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('event.organizer', function (Builder $query) {
                $query->whereIn(
                    'organizations.id',
                    auth()->user()->organizations()->pluck('id')
                );
            });
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_id')
                    ->label(__('fields.event_id'))
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('certificate_holder_id')
                    ->label(__('fields.certificate_holder_id'))
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('serial')
                    ->label(__('fields.serial'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('issued_at')
                    ->label(__('fields.issued_at')),

                Forms\Components\TextInput::make('status')
                    ->label(__('fields.status'))
                    ->required(),

                Forms\Components\Toggle::make('has_payment_issue')
                    ->label(__('fields.has_payment_issue'))
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_id')
                    ->label(__('fields.event_id'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('certificate_holder_id')
                    ->label(__('fields.certificate_holder_id'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('serial')
                    ->label(__('fields.serial'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('issued_at')
                    ->label(__('fields.issued_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('fields.status')),

                Tables\Columns\IconColumn::make('has_payment_issue')
                    ->label(__('fields.has_payment_issue'))
                    ->boolean(),

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
                    ->label('ویرایش'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف گروهی'),
                ]),
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
