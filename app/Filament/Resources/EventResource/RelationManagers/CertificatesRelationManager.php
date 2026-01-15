<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\CertificateHolder;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Support\Facades\DB;

class CertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';

    protected static ?string $title = 'گواهینامه‌های صادر شده';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('certificate_holder_id')
                ->label('کاربر موجود')
                ->relationship('certificateHolder', 'first_name')
                ->searchable()
                ->preload()

                ->getSearchResultsUsing(function (string $search): array {
                    return CertificateHolder::query()
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('national_code', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->limit(10)
                        ->get()
                        ->mapWithKeys(fn ($holder) => [
                            $holder->id => "{$holder->first_name} {$holder->last_name} ({$holder->national_code})"
                        ])
                        ->toArray();
                })

                ->getOptionLabelUsing(fn ($value) =>
                CertificateHolder::whereKey($value)
                    ->selectRaw("CONCAT(first_name,' ',last_name) as full_name")
                    ->value('full_name')
                )
            ,
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('certificateHolder.full_name')

                    ->label('دارنده'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                ->date('Y/m/d')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('addNewCertificateHolder')
                    ->label('افزودن گواهینامه برای فرد جدید')
                    ->icon('heroicon-o-plus')
                    // ۱. نام فیلدها را ساده بگذارید (بدون نقطه)
                    ->form([
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('fields.user_name'))
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label(__('fields.last_name'))
                            ->required(),
                        Forms\Components\TextInput::make('national_code')
                            ->label(__('fields.national_code'))
                            ->requiredWithout('mobile')
                            ->unique('certificate_holders', 'national_code')
                            ->validationMessages([
                                'unique' => 'این کد ملی قبلاً ثبت شده است.',
                                'required_without' => 'وارد کردن کد ملی یا شماره موبایل الزامی است.',
                            ]),
                        Forms\Components\TextInput::make('email')
                            ->label(__('fields.email'))
                            ->email()
                            ->unique('certificate_holders', 'email')
                            ->validationMessages([
                                'unique' => 'این ایمیل قبلاً ثبت شده است.',
                                'email' => 'فرمت ایمیل صحیح نیست.',
                            ]),
                        Forms\Components\TextInput::make('mobile')
                        ->label(__('fields.mobile'))
                            ->requiredWithout('national_code')
                            ->unique('certificate_holders', 'mobile')
                            ->validationMessages([
                                'unique' => 'این شماره موبایل قبلاً ثبت شده است.',
                            ]),
                    ])
                    // ۲. متد جادویی برای مدیریت ذخیره‌سازی سفارشی
                    ->action(function (array $data, RelationManager $livewire) {
                        DB::transaction(function () use ($data, $livewire){
                            // مرحله اول: ساخت دارنده گواهبنامه
                            $holder = CertificateHolder::create([
                                'first_name'    => $data['first_name'],
                                'last_name'     => $data['last_name'],
                                'national_code' => $data['national_code'],
                                'mobile'        => $data['mobile'],
                                'email'         => $data['email'],
                            ]);

                            // مرحله دوم: ساخت گواهینامه و اتصال به رویداد فعلی
                            $livewire->getRelationship()->create([
                                'certificate_holder_id' => $holder->id,
//                            TODO: dynamization status
                                'status'    => 'active',
                            ]);
                        });

                    })
                    ->successNotificationTitle('فرد جدید ثبت و گواهینامه صادر شد'),

                Tables\Actions\CreateAction::make()
                    ->label('افزودن گواهینامه برای کاربر موجود')
                    ->modalHeading('صدور گواهینامه برای کاربر سیستمی')
                ,
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

//    در اینجا مشخص می کنیم که در باکسی که بخاطر رابطه ایجاد شده است فقط مشاهده باشد یا ویراش هم مقدور باشد
    public function isReadOnly(): bool
    {
        return false;
    }
}
