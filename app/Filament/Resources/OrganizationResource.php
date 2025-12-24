<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $modelLabel = 'سازمان';
    protected static ?string $pluralLabel = 'سازمان‌ها';
    protected static ?string $navigationLabel = 'سازمان‌ها';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('fields.organize_name'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->label(__('fields.organize_slug'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('logo_path')
                    ->label(__('fields.logo'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label(__('fields.email'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->label(__('fields.website'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('fields.phone'))
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->label(__('fields.mobile'))
                    ->maxLength(10),
                Forms\Components\Textarea::make('address')
                    ->label(__('fields.address'))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('fields.status'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('fields.organize_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('fields.organize_slug'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label(__('fields.website'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('fields.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('fields.mobile'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('fields.status'))
                    ->boolean(),
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
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
