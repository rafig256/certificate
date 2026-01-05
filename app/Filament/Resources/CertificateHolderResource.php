<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateHolderResource\Pages;
use App\Models\CertificateHolder;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class CertificateHolderResource extends Resource
{
    protected static ?string $model = CertificateHolder::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'دارندگان گواهینامه';
    protected static ?string $pluralModelLabel = 'دارندگان گواهینامه';
    protected static ?string $modelLabel = 'دارنده گواهینامه';

    protected static ?string $navigationGroup = 'کاربران';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات هویتی')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('fields.user_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label(__('fields.last_name'))
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('avatar_path')
                            ->label(__('fields.user_avatar'))
                            ->image()
                            ->disk('public')
                            ->directory('user/avatars')
                            ->imageEditor()
                            ->imageCropAspectRatio('3:4')
                            ->maxSize(512) // 0.5MB
                            ->nullable()
                            ->previewable(false)
                            ,
                        Placeholder::make('current_avatar_preview')
                            ->label(__('fields.current_avatar'))
                            ->content(function ($record) {
                                if (! $record?->avatar_path) {
                                    return '<em>' . __('No avatar uploaded.') . '</em>';
                                }
                                $url = asset('storage/'.$record->avatar_path);
                                return new HtmlString(
                                    "<img src=\"{$url}\" style=\"max-width:150px; height:auto; border-radius:6px;\" />"
                                );
                            })
                            ->hiddenOn(['create']),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('اطلاعات تماس / شناسایی')
                    ->schema([
                        Forms\Components\TextInput::make('national_code')
                            ->label(__('fields.national_code'))
                            ->requiredWithout('mobile')
                            ->maxLength(10)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('mobile')
                            ->label(__('fields.mobile'))
                            ->requiredWithout('national_code')
                            ->tel()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('email')
                            ->label(__('fields.email'))
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->description('حداقل یکی از «کد ملی» یا «شماره موبایل» باید وارد شود'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('fields.user_name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('fields.last_name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('national_code')
                    ->label(__('fields.national_code'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('fields.mobile'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user_id')
                    ->label('کاربر سامانه')
                    ->formatStateUsing(fn ($state) => $state ? 'متصل شده' : '—')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('ویرایش'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف گروهی'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificateHolders::route('/'),
            'create' => Pages\CreateCertificateHolder::route('/create'),
            'edit' => Pages\EditCertificateHolder::route('/{record}/edit'),
        ];
    }
}
