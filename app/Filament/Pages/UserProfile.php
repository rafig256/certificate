<?php

namespace App\Filament\Pages;

use App\Models\CertificateHolder;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserProfile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.user-profile';

    public $name,$mobile,$national_code,$email,$certificate_holder_id,$ch_first_name,$ch_last_name,$ch_mobile,$ch_avatar_path;

    public static function getNavigationLabel(): string
    {
        return __('fields.my_profile');
    }

    public function getTitle(): string
    {
        return __('fields.my_profile');
    }

    public function mount(): void
    {
        $user = Auth::user();
        $data = [
            'name' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'national_code' => $user->national_code,
        ];
        if ($user->certificateHolder) {
            $data = array_merge($data, [
                'ch_first_name'   => $user->certificateHolder->first_name,
                'ch_last_name'    => $user->certificateHolder->last_name,
                'ch_mobile'       => $user->certificateHolder->mobile,
                'ch_avatar_path'  => $user->certificateHolder->avatar_path,
            ]);
        }
        $this->form->fill($data);
    }

    protected function getFormSchema(): array
    {
        $user = Auth::user();
        $connectedHolder = $user->certificateHolder;

        return [
            TextInput::make('name')
                ->required()
                ->label(__('fields.full_name')),

            TextInput::make('mobile')
                ->label(__('fields.mobile'))
                ->requiredWithout('national_code')
                ->rule('regex:/^0?9\d{9}$/')
                ->dehydrateStateUsing(fn($state) => ltrim($state, '0'))
                ->validationMessages([
                    'required_without' => 'وارد کردن موبایل یا کد ملی الزامی است.',
                    'regex' => 'فرمت موبایل نامعتبر است.',
                ]),

            TextInput::make('national_code')
                ->label(__('fields.national_code'))
                ->requiredWithout('mobile')
                ->rule('digits:10')
                ->validationMessages([
                    'required_without' => 'وارد کردن موبایل یا کد ملی الزامی است.',
                    'digits' => 'کد ملی باید ۱۰ رقم باشد.',
                ]),

            TextInput::make('email')
                ->label(__('fields.email')),

            // نمایش فقط اگر هنوز وصل نشده
            Select::make('certificate_holder_id')
                ->label(__('fields.certificate_holder_id'))
                ->options(function () use ($user) {
                    $options = collect();

                    if ($user->national_code) {
                        $options = CertificateHolder::query()
                            ->whereNull('user_id')
                            ->where('national_code', $user->national_code)
                            ->get();
                    }

                    if ($options->isEmpty() && $user->mobile) {
                        $options = CertificateHolder::query()
                            ->whereNull('user_id')
                            ->where('mobile', $user->mobile)
                            ->get();
                    }

                    return $options->mapWithKeys(fn ($holder) => [
                        $holder->id => $holder->first_name . ' ' . $holder->last_name
                    ])->toArray();
                })
                ->searchable()
                ->placeholder('انتخاب دارنده گواهینامه')
                ->visible(!$connectedHolder),

            // نمایش holder وصل شده به صورت متن
            \Filament\Forms\Components\Placeholder::make('certificate_holder_connected')
                ->label('دارنده گواهینامه متصل شده')
                ->content(fn() => $connectedHolder
                    ? $connectedHolder->first_name . ' ' . $connectedHolder->last_name
                    : '')
                ->visible($connectedHolder !== null),

            Fieldset::make('اطلاعات دارنده گواهینامه')
                ->visible(fn () => $user->certificateHolder !== null)
                ->schema([

                    TextInput::make('ch_first_name')
                        ->label('نام روی گواهینامه'),

                    TextInput::make('ch_last_name')
                        ->label('فامیل روی گواهینامه'),

                    TextInput::make('ch_mobile')
                        ->label(__('fields.mobile'))
                    ,

                    FileUpload::make('ch_avatar_path')
                        ->label(__('fields.user_avatar'))
                        ->image()
                        ->disk('public')
                        ->directory('user/avatars')
                        ->imageEditor()
                        ->imageCropAspectRatio('3:4')
                        ->maxSize(512) // 0.5MB
                        ->nullable()
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Get $get) {
                            return trim(
                                    ($get('ch_first_name') ?? 'user') . ' ' .
                                    ($get('ch_last_name') ?? '')
                                ) . '-' . now()->format('Ymd') . '.' . $file->getClientOriginalExtension();
                        })
                    ,
                ]),
        ];
    }



    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        DB::transaction(function () use ($data, $user) {
            $user->update([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'national_code' => $data['national_code'],
                'email' => $data['email'],
            ]);

            $holder = $user->certificateHolder;

            if (!$holder && !empty($data['certificate_holder_id'])) {
                $holder = CertificateHolder::where('id', $data['certificate_holder_id'])
                    ->whereNull('user_id')
                    ->first();

                if ($holder) {
                    $holder->update([
                        'user_id' => $user->id,
                    ]);
                }
            }

            if ($holder) {
                $holder->update([
                    'first_name'    => $data['ch_first_name'],
                    'last_name'     => $data['ch_last_name'],
                    'national_code' => $data['national_code'],
                    'email'         => $data['email'],
                    'mobile'        => $data['ch_mobile'],
                    'avatar_path'   => $data['ch_avatar_path'] ?? $holder->avatar_path,
                ]);
            };
        });


        Notification::make()
            ->title('موفق')
            ->body('پروفایل با موفقیت ذخیره شد')
            ->success()
            ->send();
    }
}
