<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignerEventResource\Pages;
use App\Filament\Resources\SignerEventResource\RelationManagers;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SignerEventResource extends Resource
{
    protected static ?string $model = Event::class;
//    protected static ?string $navigationGroup = 'مدیریت گواهینامه';

    protected static ?string $navigationLabel = "امضای رویداد";
    protected static ?string $modelLabel = "امضای رویداد";
    protected static ?string $pluralLabel = "امضای رویدادها";
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('fields.title'))
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        return $state . '<br><span class="text-gray-500 text-sm">دسته: ' . ($record->category?->name ?? '-') . '</span>';
                    })
                    ->html(),

                TextColumn::make('start_at')
                    ->label(__('fields.times'))
                    ->formatStateUsing(function ($state, $record) {
                        $start = Carbon::parse($record->start_at)->format('Y-m-d H:i');
                        $end = Carbon::parse($record->end_at)->format('Y-m-d H:i');
                        return $start . '<br><span class="text-gray-500 text-sm">پایان: ' . $end . '</span>';
                    })
                    ->html(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn($state) => match($state) {
                        'Draft' => __('fields.status_draft'),
                        'PendingPayment' => __('fields.status_pending_payment'),
                        'Active' => __('fields.status_active'),
                        'Completed' => __('fields.status_completed'),
                        'Closed' => __('fields.status_closed'),
                        'Canceled' => __('fields.status_canceled'),
                        default => $state,
                    })
                    ->colors([
                        'Draft' => 'secondary',
                        'Closed' => 'danger',
                        'Canceled' => 'warning',
                    ]),

                TextColumn::make('organizer')
                    ->label(__('fields.organizer'))
                    ->formatStateUsing(function ($state, $record) {
                        return ($record->organizer?->name ?? '-') . '<br>' . $record->location;
                    })
                    ->html(),

                Tables\Columns\IconColumn::make('has_exam')
                    ->label(__('fields.has_exam'))
                    ->boolean(),

                TextColumn::make('signatories_count')
                    ->label('تعداد امضا')
                    ->alignCenter(),
            ])
            ->actions([
                Tables\Actions\Action::make('sign')
                    ->label('امضا کنید')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->action(fn ($record) =>
                    $record->signatories()->attach(Auth::id())
                    )
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                    ! $record->signatories()
                        ->where('signatories.id', Auth::id())
                        ->exists()
                    ),

                Tables\Actions\Action::make('unsign')
                    ->label('حذف امضا')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn ($record) =>
                    $record->signatories()->detach(Auth::id())
                    )
                    ->requiresConfirmation()
                    ->visible(fn ($record) =>
                    $record->signatories()
                        ->where('signatories.id', Auth::id())
                        ->exists()
                    ),
            ]);


    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('signatories')
            ->whereNotIn('status', ['Closed', 'Canceled', 'Completed']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignerEvents::route('/'),
            'create' => Pages\CreateSignerEvent::route('/create'),
            'edit' => Pages\EditSignerEvent::route('/{record}/edit'),
        ];
    }
}
