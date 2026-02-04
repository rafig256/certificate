<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\User;
use App\Models\WalletTransaction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class AdminWalletManager extends Page
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.admin-wallet-manager';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'شارژ کیف پول';
    protected static ?string $navigationGroup = 'مدیریت پنل';


    public static function getNavigationLabel(): string
    {
        return __('fields.wallet_management');
    }
    public function getTitle(): string
    {
        return __('fields.wallet_charge');
    }


    public $user_id;
    public $amount;
    public $type; // credit / debit
    public $description;

    public static function shouldRegisterNavigation(): bool
    {
        // فقط ادمین
        return auth()->user()->hasRole('administrator');
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('کاربر')
                ->options(User::query()->pluck('name', 'id'))
                ->preload()
                ->searchable()
                ->required(),

            TextInput::make('amount')
                ->label('مبلغ')
                ->numeric()
                ->required(),

            Select::make('type')
                ->label('عملیات')
                ->options([
                    'credit' => 'اضافه کردن',
                    'debit'  => 'کسر کردن',
                ])
                ->required(),

            TextInput::make('description')
                ->label('توضیحات')
                ->nullable(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('submit')
                ->label(__('fields.save'))
                ->icon('heroicon-o-check')
                ->action('submit')
                ->color('success'),
        ];
    }


    public function submit()
    {
        $data = $this->form->getState();

        DB::transaction(function() use ($data) {
            $wallet = User::findOrFail($data['user_id'])->wallet;

            if ($data['type'] === 'debit' && $wallet->balance < $data['amount']) {
                throw new \Exception('موجودی کافی نیست.');
            }

            $type = 'adjustment'; // ثبت مقدار پیشفرض
            // آپدیت balance
            if ($data['type'] === 'credit'){
                $wallet->balance += $data['amount'];
                $type = 'deposit';
            }elseif ($data['type'] === 'debit'){
                $wallet->balance -= $data['amount'];
                $type = 'withdraw';
            }
            $wallet->save();

            $payment = Payment::create([
                'payer_user_id'         => null,
                'performed_by_user_id'  => auth()->id(),
                'amount'                => $data['amount'],
                'method'                => 'admin',
                'paid_at'               => now(),
            ]);

            // ثبت تراکنش
            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'amount'      => $data['amount'],
                'type'        => $type,
                'description' => $data['description'] ?? null,
            ]);
        });

        Notification::make()
            ->title('موفق')
            ->body('عملیات کیف پول با موفقیت انجام شد.')
            ->success()
            ->send();

        $this->form->fill([]);
    }
}
