<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactMeResource\Pages;
use App\Filament\Admin\Resources\ContactMeResource\RelationManagers;
use App\Models\ContactMe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactMeResource extends Resource
{
    protected static ?string $model = ContactMe::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?string $navigationLabel = 'Messages';

    protected static ?string $modelLabel = 'Contact Message';

    protected static ?string $pluralModelLabel = 'Contact Messages';

    protected static ?string $slug = 'contact-me';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('message_body')
                    ->label('Message')
                    ->limit(80)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(
                        fn(ContactMe $record): string =>
                        $record->read_at === null ? 'Unread' : 'Read'
                    )
                    ->badge()
                    ->color(
                        fn(string $state): string =>
                        match ($state) {
                            'Unread' => 'warning',
                            'Read' => 'success',
                        }
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListContactMe::route('/'),
            'view' => Pages\ViewContactMe::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Sender')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),

                        Infolists\Components\TextEntry::make('email')
                            ->copyable(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Message')
                    ->schema([
                        Infolists\Components\TextEntry::make('message_body')
                            ->label('')
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Received At')
                            ->dateTime(),
                    ]),
            ]);
    }
}
