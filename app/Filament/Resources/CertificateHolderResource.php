<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateHolderResource\Pages;
use App\Models\CertificateHolder;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CertificateHolderResource extends Resource
{
    protected static ?string $model = CertificateHolder::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'دارندگان گواهینامه';
    protected static ?string $pluralModelLabel = 'دارندگان گواهینامه';
    protected static ?string $modelLabel = 'دارنده گواهینامه';

    protected static ?string $navigationGroup = 'کاربران';
    protected static ?int $navigationSort = 2;


    public static function canViewAny(): bool
    {
        return auth()->user()->can('certificate_holder.view');
    }

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
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, Forms\Get $get){
                                return (string) $get('first_name') ." ". $get('last_name') ."-". Carbon::now()->format('Ymd') .".". $file->getClientOriginalExtension();
                            })
                            ,

                        Select::make('user_id')
                            ->label(__('fields.connect_to_user'))
                            ->placeholder('انتخاب کاربر')
                            ->searchable()
                            ->options(function (callable $get, $record) {

                                if ($record?->user_id) {
                                    return [];
                                }

                                if (! auth()->user()?->can('certificate_holder.link_user')) {
                                    return [];
                                }

                                if (! $record) {
                                    return [];
                                }

                                if (! $record->mobile && ! $record->national_code) {
                                    return [];
                                }

                                return User::query()
                                    ->whereNotIn('id', function ($q) {
                                        $q->select('user_id')
                                            ->from('certificate_holders')
                                            ->whereNotNull('user_id');
                                    })
                                    ->where(function ($query) use ($record) {
                                        if ($record->mobile) {
                                            $query->orWhere('mobile', $record->mobile);
                                        }

                                        if ($record->national_code) {
                                            $query->orWhere('national_code', $record->national_code);
                                        }
                                    })
                                    ->pluck('name', 'id');

                            })
                            ->visible(fn ($record) =>
                                is_null($record?->user_id)
                                && auth()->user()?->can('certificate_holder.link_user')
                            )
                            ->disabled(fn ($record) => filled($record?->user_id))
                            ->helperText('پس از اتصال، امکان تغییر وجود ندارد')
                        ,

                        Placeholder::make('connected_user')
                            ->label('کاربر سامانه')
                            ->content(fn ($record) =>
                            $record?->user
                                ? $record->user->name
                                : '—'
                            )
                            ->visible(fn ($record) => filled($record?->user_id))
                        ,

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
                    ->label(__('fields.full_name'))
                    ->formatStateUsing(fn($record):string => $record->first_name ." ".$record->last_name)
                    ->searchable(),

                ImageColumn::make('avatar_path')
                    ->label('عکس')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('national_code')
                    ->label(__('fields.national_code'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('fields.mobile'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('کاربر سامانه')
                    ->placeholder('—')
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
