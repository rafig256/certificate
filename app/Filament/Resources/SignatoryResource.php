<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignatoryResource\Pages;
use App\Filament\Resources\SignatoryResource\RelationManagers;
use App\Models\Signatory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class SignatoryResource extends Resource
{
    protected static ?string $model = Signatory::class;

// عنوان تک‌تک رکوردها (مثلاً دکمه "ایجاد امضاکننده")
    protected static ?string $modelLabel = 'امضاکننده';

// عنوان جمع (برای تیتر صفحه لیست و منو اگر navigationLabel نباشد)
    protected static ?string $pluralLabel = 'امضاکنندگان';

// عنوان دقیق منو
    protected static ?string $navigationLabel = 'امضاکنندگان';

// آیکون و ترتیب
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 2;

// این مورد برای آدرس URL است (مثلاً /admin/signatories) - بگذار بماند اما انگلیسی باشد بهتر است
    protected static ?string $slug = 'signatories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // بخش اطلاعات اصلی در یک Card یا Section (اختیاری اما زیباتر)
                Forms\Components\Section::make('اطلاعات امضاکننده')
                    ->schema([
                        TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('تلفن')
                            ->tel()
                            ->maxLength(20),

                        Select::make('type')
                            ->label('نوع')
                            ->options([
                                'علمی' => 'علمی',
                                'دولتی' => 'دولتی',
                                'فنی و حرفه ای' => 'فنی و حرفه ای',
                                'پارک علم' => 'پارک علم',
                                'سایر' => 'سایر',
                            ])
                            ->required(),


                        FileUpload::make('logo_path')
                            ->label(__('fields.logo'))
                            ->directory('signer')
                            ->visibility('public')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imagePreviewHeight(150)
                            ->preserveFilenames()
                            ->maxSize(1024)
                        ,

                        FileUpload::make('sign_path')
                            ->label(__('fields.sing_path_upload'))
                            ->directory('signer')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/png'])
                            ->imageResizeMode('cover')
                            ->imagePreviewHeight(150)
                            ->preserveFilenames()
                            ->maxSize(1024)
                            ->helperText('فقط فرمت png مجاز است')
                        ,

                        Select::make('user_id')
                            ->label('کاربر مرتبط')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('level')
                            ->label('سطح')
                            ->numeric()
                            ->default(1),

                        TextInput::make('responsible')
                            ->label(__('fields.responsible_person_name'))
                            ->maxLength(100),

                     ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('تلفن'),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge() // نمایش به صورت نشان رنگی
                    ->color('info'),

                TextColumn::make('level')
                    ->label('سطح')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // اگر بخواهی بر اساس 'type' فیلتر کنی:
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'علمی' => 'علمی',
                        'دولتی' => 'دولتی',
                        'فنی و حرفه ای' => 'فنی و حرفه ای',
                        'پارک علم' => 'پارک علم',
                        'سایر' => 'سایر',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSignatories::route('/'),
            'create' => Pages\CreateSignatory::route('/create'),
            'edit' => Pages\EditSignatory::route('/{record}/edit'),
        ];
    }
}
