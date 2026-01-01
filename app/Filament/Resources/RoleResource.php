<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'نقش‌ها';
    protected static ?string $pluralLabel = 'نقش‌ها';
    protected static ?string $modelLabel = 'نقش';
    protected static ?string $navigationGroup = 'کاربران';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات نقش')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام نقش (انگلیسی)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('مثال: admin ، editor ، support'),

                        Forms\Components\TextInput::make('guard_name')
                            ->label('گارد')
                            ->default('web')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('دسترسی‌ها')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('انتخاب دسترسی‌ها')
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name'
                            )
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام نقش')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label('گارد')
                    ->sortable(),

                Tables\Columns\TextColumn::make('permissions.name')
                    ->label('دسترسی‌ها')
                    ->badge()
                    ->separator(',')
                    ->wrap(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
