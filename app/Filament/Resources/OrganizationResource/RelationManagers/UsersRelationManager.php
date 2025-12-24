<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
// عنوان تب یا باکس در صفحه سازمان
    protected static ?string $title = 'کاربران سازمان';

    // عنوان مدل در دکمه‌ها (مثلاً "اتصال کاربر")
    protected static ?string $modelLabel = 'کاربر';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('role')
                ->label('نقش کاربر در سازمان'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام کاربر'),
                Tables\Columns\TextColumn::make('role')->label('نقش'), // نمایش نقش در جدول
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make() // برای متصل کردن کاربر موجود به سازمان
                ->form(fn (Tables\Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\TextInput::make('role')->label('نقش'), // مقداردهی پیوت موقع اتچ کردن
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // ویرایش رکورد پیوت
                Tables\Actions\DetachAction::make(), // جدا کردن کاربر از سازمان
            ]);
    }
}
