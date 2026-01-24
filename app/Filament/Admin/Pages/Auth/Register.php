<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Filament\Forms;


class Register extends BaseRegister
{

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('fields.full_name'))
                ->required(),

            Forms\Components\TextInput::make('national_code')
                ->label('کد ملی')
                ->required()
                ->unique('users', 'national_code')
                ->length(10),

            Forms\Components\TextInput::make('email')
                ->label('ایمیل')
                ->email()
                ->nullable(),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->confirmed(),

            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->required(),
        ]);
    }

    public static function canRegister(): bool
    {
        return true;
    }


}
