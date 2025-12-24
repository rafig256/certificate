<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // آیکون را به کاربران تغییر دادم

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) // فقط اگر پر شده باشد ذخیره شود
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('national_code')
                    ->label('کد ملی')
                    ->length(10)
                    ->unique(ignoreRecord: true) // جلوگیری از خطای تکراری هنگام ویرایش همان کاربر
                    ->nullable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ عضویت')
                    ->dateTime('Y/m/d')
                    ->sortable(),
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
}
