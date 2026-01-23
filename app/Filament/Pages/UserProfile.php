<?php

namespace App\Filament\Pages;

use App\Models\CertificateHolder;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'پروفایل من';
    protected static ?string $title = 'پروفایل من';

    protected static string $view = 'filament.pages.user-profile';

    public $name;
    public $mobile;
    public $national_code;
    public $certificate_holder_id;

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'name' => $user->name,
            'mobile' => $user->mobile,
            'national_code' => $user->national_code,
            'certificate_holder_id' => $user->certificate_holder_id,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required()->label('نام'),
            TextInput::make('mobile')->required()->label('موبایل'),
            TextInput::make('national_code')->label('کد ملی'),
            Select::make('certificate_holder_id')
                ->label('دارنده گواهینامه')
                ->options(function () {
                    $user = Auth::user();
                    return CertificateHolder::query()
                        ->when($user->mobile, fn($q) => $q->where('mobile', $user->mobile))
                        ->orWhere(function($q) use ($user) {
                            $q->where('national_code', $user->national_code);
                        })
                        ->get()
                        ->mapWithKeys(function ($holder) {
                            return [$holder->id => $holder->first_name . ' ' . $holder->last_name];
                        })->toArray();
                })
                ->searchable()
                ->placeholder('انتخاب دارنده گواهینامه'),
        ];
    }


    public function save(): void
    {
        $data = $this->form->getState();
        Notification::make()
            ->title('موفق')
            ->body('پروفایل با موفقیت ذخیره شد'.$data['national_code'])
            ->success()
            ->send();

        Auth::user()->update([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'national_code' => $data['national_code'],
            'certificate_holder_id' => $data['certificate_holder_id'],
        ]);
    }
}
