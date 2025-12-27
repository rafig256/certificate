<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'کاربران رویداد';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('کاربر')
                    ->options(function () {
                        return User::query()
                            ->whereNotIn(
                                'id',
                                $this->getOwnerRecord()->users()->pluck('users.id')
                            )
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('national_code')
            ->label(__('fields.national_code')),

                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('تاریخ عضویت')
                    ->dateTime('Y/m/d'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('افزودن کاربر')
                    ->recordTitleAttribute('name') // نمایش نام کاربر در لیست و قابلیت جستجو
                    ->modalHeading('افزودن کاربر به رویداد')
                    ->modalSubmitActionLabel('افزودن')
                    ->preloadRecordSelect()
                    ->disabled(fn () => $this->isLocked()),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('حذف از رویداد')
                    ->disabled(fn () => $this->isLocked()),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ])
            ->emptyStateHeading('کاربری ثبت نشده است');
    }

    protected function isLocked(): bool
    {
        return in_array(
            $this->getOwnerRecord()->status,
            ['Closed', 'Canceled']
        );
    }
}
