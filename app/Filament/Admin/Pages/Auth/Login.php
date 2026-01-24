<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms;
use Filament\Forms\Form;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('national_code')
                ->label('کد ملی')
                ->required()
                ->maxLength(10),

            Forms\Components\TextInput::make('password')
                ->label('رمز عبور')
                ->password()
                ->required(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'national_code' => $data['national_code'],
            'password' => $data['password'],
        ];
    }
}
