<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\{RepeatableEntry, Section, Grid, IconEntry};


class EventView extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                /* =======================
                 | اطلاعات اصلی رویداد
                 ======================= */
                Section::make('اطلاعات رویداد')
                    ->schema([
                        TextEntry::make('title')
                            ->label('عنوان')
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('category.name')
                            ->label('دسته‌بندی')
                            ->prefix('دسته: '),

                        TextEntry::make('description')
                            ->label('توضیحات')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),


                /* =======================
                 | زمان و مکان
                 ======================= */
                Section::make('زمان و مکان')
                    ->schema([
                        TextEntry::make('start_at')
                            ->label('زمان شروع')
                            ->dateTime('Y/m/d H:i'),

                        TextEntry::make('end_at')
                            ->label('زمان پایان')
                            ->dateTime('Y/m/d H:i'),

                        TextEntry::make('location')
                            ->label('محل برگزاری')
                            ->icon('heroicon-o-map-pin'),

                        TextEntry::make('link')
                            ->label('لینک')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->visible(fn ($state) => filled($state)),
                    ])
                    ->columns(2),


                /* =======================
                 | وضعیت و سطح
                 ======================= */
                Section::make('وضعیت')
                    ->schema([
                        TextEntry::make('status')
                            ->label('وضعیت')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'Draft' => 'gray',
                                'PendingPayment' => 'warning',
                                'Active' => 'success',
                                'Completed' => 'info',
                                'Closed'=> 'danger',
                                'Canceled' => 'danger',
                            }),

                        TextEntry::make('level')
                            ->label(__('fields.level'))
                            ->formatStateUsing(fn ($state) => $state?->label()),
                    ])
                    ->columns(2),


                /* =======================
                 | پرداخت
                 ======================= */
                Section::make('پرداخت')
                    ->schema([
                        TextEntry::make('payment_mode')
                            ->label('روش پرداخت'),

                        TextEntry::make('price_per_person')
                            ->label('هزینه هر نفر')
                            ->formatStateUsing(fn ($state) =>
                            $state == 0 ? 'رایگان' : number_format($state) . ' تومان'
                            ),
                    ])
                    ->columns(2),


                /* =======================
                 | گواهینامه
                 ======================= */
                Section::make('گواهینامه')
                    ->schema([
                        TextEntry::make('template.name')
                            ->label('قالب گواهینامه'),

                        TextEntry::make('certificate_text')
                            ->label('متن گواهینامه')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),


                /* =======================
                 | سایر
                 ======================= */
                Section::make('سایر تنظیمات')
                    ->schema([
                        IconEntry::make('has_exam')
                            ->label('دارای آزمون')
                            ->boolean(),
                    ]),

                Section::make('امضاکنندگان')
                    ->schema([
                        RepeatableEntry::make('signatories')
                            ->label('')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('نام'),

                                TextEntry::make('email')
                                    ->label('ایمیل')
                                    ->visible(fn ($state) => filled($state)),

                                TextEntry::make('phone')
                                    ->label('تلفن')
                                    ->visible(fn ($state) => filled($state)),

                                TextEntry::make('type')
                                    ->label('نوع')
                                    ->visible(fn ($state) => filled($state)),

                                TextEntry::make('level')
                                    ->label(__('fields.level'))
                                    ->visible(fn ($state) => filled($state)),
                                TextEntry::make('user_id')
                                    ->label(__('fields.signature_admin_user'))
                                    ->visible(fn ($state) => filled($state)),
                            ])
                            ->columns(2),
                    ])
                    ->visible(fn ($record) => $record->signatories()->exists()),
            ]);
    }
}
