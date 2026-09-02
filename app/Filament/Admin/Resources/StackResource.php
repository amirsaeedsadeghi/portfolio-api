<?php

namespace App\Filament\Admin\Resources;

use App\Enums\AssetTypeEnum;
use App\Filament\Admin\Resources\StackResource\Pages;
use App\Filament\Admin\Resources\StackResource\RelationManagers;
use App\Models\Stack;
use App\Support\AssetStorageResolver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StackResource extends Resource
{
    protected static ?string $model = Stack::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?string $navigationLabel = 'Tech Stacks';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Technology')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('image')
                            ->label('Logo')
                            ->disk(
                                AssetStorageResolver::diskName(
                                    AssetTypeEnum::STACK
                                )
                            )
                            ->directory(
                                AssetStorageResolver::directory(
                                    AssetTypeEnum::STACK
                                )
                            )
                            ->image()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/svg+xml',
                            ])
                            ->maxSize(2048)
                            ->visibility('public')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Logo')
                    ->disk(
                        AssetStorageResolver::diskName(
                            AssetTypeEnum::STACK
                        )
                    )
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStacks::route('/'),
            'create' => Pages\CreateStack::route('/create'),
            'edit' => Pages\EditStack::route('/{record}/edit'),
        ];
    }
}
