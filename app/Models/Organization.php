<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function signatories(){
        return $this->belongsToMany(Signatory::class)->withTimestamps();
    }

    public static function GetForm(): array
    {
    return
    [
        Section::make(__('fields.organ_information'))
            ->columns(2)
            ->icon('heroicon-o-information-circle')
            ->collapsible()
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(
                        table: Organization::class,
                        column: 'name',
                        ignoreRecord: true
                    )
                    ->label(__('fields.organize_name'))
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(
                        table: Organization::class,
                        column: 'slug',
                        ignoreRecord: true
                    )
                    ->label(__('fields.organize_slug'))
                    ->maxLength(255),

                FileUpload::make('logo_path')
                    ->label(__('fields.logo'))
                    ->directory('organ')
                    ->visibility('public')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imagePreviewHeight(150)
                    ->preserveFilenames()
                    ->maxSize(1024),
                Textarea::make('address')
                    ->label(__('fields.address'))
                    ->rows(3),
                Fieldset::make('وضعیت')
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('fields.status'))
                            ->required()
                            ->inline(false),
                    ]),
            ]),
        Section::make(__('fields.contact_information'))
            ->columns(2)
            ->collapsible()
            ->schema([
                TextInput::make('email')
                    ->email()
                    ->unique(
                        table: Organization::class,
                        column: 'email',
                        ignoreRecord: true
                    )
                    ->label(__('fields.email'))
                    ->maxLength(255),
                TextInput::make('website')
                    ->label(__('fields.website'))
                    ->unique(
                        table: Organization::class,
                        column: 'website',
                        ignoreRecord: true
                    )
                    ->suffix('https://www.')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(__('fields.phone'))
                    ->unique(
                        table: Organization::class,
                        column: 'phone',
                        ignoreRecord: true
                    )
                    ->tel()
                    ->maxLength(255),
                TextInput::make('mobile')
                    ->label(__('fields.mobile'))
                    ->unique(
                        table: Organization::class,
                        column: 'mobile',
                        ignoreRecord: true
                    )
                    ->maxLength(11),
            ]),


        Repeater::make('users')
            ->label(__('fields.select_users'))
            ->hiddenOn('edit')
            ->collapsible()
            ->addActionLabel('افزودن کاربر')
            ->columnSpanFull()
            ->schema([
                Select::make('user_id')
                    ->label(__('fields.admin_name'))
                    ->options(User::pluck('name', 'id'))
                    ->required(),
                TextInput::make('role')
                    ->label(__('fields.admin_role_name'))
                    ->required(),
            ]),


    ];
    }

    public function active(){
        $this->update(['is_active' => true]);
    }

    public function unactivated()
    {
        $this->update(['is_active' => false]);
    }
}
