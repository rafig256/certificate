<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Category;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationLabel = 'رویدادها';
    protected static ?string $modelLabel = 'رویداد';
    protected static ?string $pluralModelLabel = 'رویدادها';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'مدیریت گواهینامه';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('fields.title'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label(__('fields.description'))
                    ->columnSpanFull(),

                Forms\Components\Select::make('category_id')
                    ->label(__('fields.category'))
                    ->options(fn () => Category::query()
                        ->orderBy('parent_id')
                        ->get()
                        ->mapWithKeys(function ($category) {
                            $prefix = $category->parent_id ? '— ' : '';
                            return [$category->id => $prefix . $category->name];
                        })
                        ->toArray()
                    )
                    ->searchable()
                    ->required(),


                Forms\Components\Select::make('level')
                    ->label(__('fields.level'))
                    ->options([
                        'Beginner' => __('fields.level_beginner'),
                        'Intermediate' => __('fields.level_intermediate'),
                        'Advanced' => __('fields.level_advanced'),
                    ])
                    ->default('Intermediate')
                    ->required(),

                Forms\Components\Select::make('organizer_id')
                    ->label(__('fields.organizer'))
                    ->relationship('organizer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DateTimePicker::make('start_at')
                    ->label(__('fields.start_at'))
                    ->required(),

                Forms\Components\DateTimePicker::make('end_at')
                    ->label(__('fields.end_at'))
                    ->required(),

                Forms\Components\Textarea::make('certificate_text')
                    ->label(__('fields.certificate_text'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('template_id')
                    ->label(__('fields.template'))
                    ->relationship('template', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('location')
                    ->label(__('fields.location'))
                    ->placeholder('مجازی یا فیزیکی')
                    ->required()
                    ->maxLength(255),

                TextInput::make('link')
                    ->label(__('fields.link'))
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label(__('fields.status'))
                    ->options([
                        'Draft' => __('fields.status_draft'),
                        'PendingPayment' => __('fields.status_pending_payment'),
                        'Active' => __('fields.status_active'),
                        'Completed' => __('fields.status_completed'),
                        'Canceled' => __('fields.status_canceled'),
                    ])
                    ->visibleOn('edit')
                    ->required(),

                Forms\Components\Select::make('payment_mode')
                    ->label(__('fields.payment_mode'))
                    ->options(function () {
                        $options = [
                            'OrganizerPays' => __('fields.payment_organizer'),
                            'ParticipantPays' => __('fields.payment_participant'),
                        ];

                        if (Auth::user()?->hasRole('administrator')) {
                            $options['Free'] = __('fields.payment_free');
                        }

                        return $options;
                    })
                    ->required()
                    ->reactive(),

                Forms\Components\TextInput::make('price_per_person')
                    ->label(__('fields.price_per_person'))
                    ->numeric()
                    ->default(0)
                    ->visible(fn ($get) => $get('payment_mode') !== 'Free')
                    ->required(fn ($get) => $get('payment_mode') !== 'Free'),

                Forms\Components\Toggle::make('has_exam')
                    ->label(__('fields.has_exam'))
                    ->default(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('fields.title'))
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        return $state . '<br><span class="text-gray-500 text-sm">دسته: ' . $record->category->name . '</span>';
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->label(__('fields.organizer'))
                    ->numeric()
                    ->formatStateUsing(function ($state, $record){
                        return $state . '<br><span class="text-gray-500 text-sm">' . $record->location . '</span>';
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('fields.start_at'))
                    ->dateTime('Y/m/d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('fields.status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'Draft' => __('fields.status_draft'),
                        'PendingPayment' => __('fields.status_pending_payment'),
                        'Active' => __('fields.status_active'),
                        'Completed' => __('fields.status_completed'),
                        'Canceled' => __('fields.status_canceled'),
                    }),

                Tables\Columns\IconColumn::make('has_exam')
                    ->label(__('fields.has_exam'))
                    ->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('نمایش')
                        ->button()
                        ->color('success'),

                    Tables\Actions\EditAction::make()
                        ->label('ویرایش')
                        ->button()
                        ->color('primary'),
                ])
                    ->label(__('fields.management'))
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('info')
                    ->button()
            ])
            ->actionsColumnLabel(__('fields.management'))

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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\EventView::route('/{record}/view'), 
        ];
    }
}
