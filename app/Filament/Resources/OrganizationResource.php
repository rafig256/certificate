<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
            ->schema(
                Organization::GetForm(),
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('fields.organize_name'))
                    ->searchable()
                ->description(fn($record) => $record->slug),
                Tables\Columns\ImageColumn::make('logo_path')
                ->label(__('fields.logo')),

                Tables\Columns\TextColumn::make('website')
                    ->label(__('fields.website'))
                    ->url(fn($state) => 'https://www.' . $state,true)
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('fields.status')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('is_active' )
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function ($record) {
                    $record->active();
                })
                ->label(__('fields.activate'))
                ->after(function(){
                    Notification::make()->success()->title(__('fields.activate_message'))
                        ->body(__('fields.activate_organ_message'))->send();
                })
                    ->visible(fn($record) => !$record->is_active),

                Tables\Actions\Action::make('unactivated')
                ->icon('heroicon-o-no-symbol')
                ->label(__('fields.unactivated'))
                ->color('danger')
                ->action(function ($record){
                    $record->unactivated();
                })
                    ->visible(fn($record) => $record->is_active)
                ->after(function(){
                    Notification::make()->danger()->title(__('fields.inactivate_message'))
                        ->body(__('fields.inactivate_organ_message'))->send();
                })
                ->requiresConfirmation(),
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
