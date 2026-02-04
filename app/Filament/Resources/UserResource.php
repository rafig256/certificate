<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'کاربر';
    protected static ?string $pluralModelLabel = 'کاربران';
    protected static ?string $navigationLabel = 'کاربران';
    protected static ?string $navigationGroup = 'کاربران';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required(),

                Forms\Components\TextInput::make('national_code')
                    ->label('کد ملی')
                    ->length(10)
                    ->unique(ignoreRecord: true) // جلوگیری از خطای تکراری هنگام ویرایش همان کاربر
                    ->nullable(),

                Forms\Components\TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) // فقط اگر پر شده باشد ذخیره شود
                    ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\TextInput::make('mobile')
                    ->label(__('fields.mobile'))
                    ->numeric()
                    ->minLength(10)
                    ->maxLength(11)
                    ->regex('/^(09|9)\d{9}$/')
                    ->dehydrateStateUsing(function ($state) {
                        // حذف صفر اول اگر وجود داشته باشد
                        return ltrim($state, '0');
                    }),

                Select::make('roles')
                    ->label('نقش‌ها')
                    ->multiple()
                    ->options(
                        Role::query()->pluck('name', 'name')
                    )
                    ->preload()
                    ->searchable()
                    ->dehydrated(false)
                    ->afterStateHydrated(function ($component, $record) {
                        if ($record) {
                            $component->state(
                                $record->roles->pluck('name')->toArray()
                            );
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id') // اضافه کردن آی‌دی برای تست
                ->label('شناسه')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jalali.created_at')
                    ->label('تاریخ عضویت')
                    ->dateTime('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('نقش ها')
                    ->separator(',')
                    ->wrap()
                    ->badge(),
                Tables\Columns\TextColumn::make('national_code')
                    ->label('کد ملی')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('users.view');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('users.create');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('users.edit');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('users.delete');
    }

}
