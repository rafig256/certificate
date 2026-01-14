<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Certificate;
use App\Models\CertificateHolder;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationLabel = 'تراکنش';
    protected static ?string $modelLabel = 'تراکنش ها';
    protected static ?string $navigationGroup = 'مدیریت پنل';
    protected static ?string $pluralModelLabel = 'تراکنش ها';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->native(false)
                    ->label(__('fields.user_name'))
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->label(__('fields.amount'))
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('type')
                    ->required()
                    ->native(false)
                    ->label(__('fields.pay_type'))
                    ->options([
                        'single' => __('fields.single_pay'),
                        'multi' => __('fields.multi_pay'),
                    ])
                ,

                Forms\Components\TextInput::make('gate')
                    ->required()
                    ->label(__('fields.gate_name'))
                    ->maxLength(255),

                Select::make('certificate_holder')
                    ->label(__('fields.certificate_holder_id'))
                    ->options(CertificateHolder::pluck('first_name', 'last_name'))
                    ->native(false)
                    ->required(),

                Select::make('certificate_holder')
                    ->relationship(
                        'certificates',
                        'certificate_holder_id',
                        fn($query) => $query
                        ),

                Forms\Components\Select::make('status')
                    ->required()
                    ->native(false)
                    ->label(__('fields.pay_status'))
                    ->options([
                        'draft' => __('fields.draft_pay'),
                        'cancel' => __('fields.cancel_pay'),
                        'reject' => __('fields.reject_pay'),
                        'payed' => __('fields.payed'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('fields.payer_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->label(__('fields.amount'))
                    ->suffix(" ".config('settings.currency_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                ->label(__('fields.pay_type_table')),

                Tables\Columns\TextColumn::make('gate')
                    ->label(__('fields.gate_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->label(__('fields.pay_status'))
                ->color(function($state) {
                        return $state->getColor();
                    })
                ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
