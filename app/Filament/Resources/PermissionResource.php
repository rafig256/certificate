<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'دسترسی‌ها';
    protected static ?string $pluralModelLabel = 'دسترسی‌ها';
    protected static ?string $modelLabel = 'دسترسی';
    protected static ?string $navigationGroup = 'کاربران';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()->can('permission.view');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('permission.create');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('permission.delete');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام دسترسی')
                    ->required()
                    ->unique(
                        table: Permission::class,
                        column: 'name',
                        ignoreRecord: true
                    )
                    ->maxLength(255),

                Forms\Components\TextInput::make('guard_name')
                    ->label('گارد')
                    ->default('web')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام دسترسی')
                    ->searchable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label('گارد'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y-m-d'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
