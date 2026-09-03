<?php

namespace App\Filament\Admin\Resources;

use App\Enums\AssetTypeEnum;
use App\Enums\UserRoleEnum;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Support\AssetStorageResolver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\Section::make('Profile Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Avatar')
                                    ->disk(
                                        AssetStorageResolver::diskName(
                                            AssetTypeEnum::AVATAR
                                        )
                                    )
                                    ->directory(
                                        AssetStorageResolver::directory(
                                            AssetTypeEnum::AVATAR
                                        )
                                    )
                                    ->image()
                                    ->imageEditor()
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                    ])
                                    ->maxSize(2048)
                                    ->visibility('public')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('role')
                            ->options(
                                collect(UserRoleEnum::cases())
                                    ->mapWithKeys(
                                        fn(UserRoleEnum $role) => [
                                            $role->value => ucfirst($role->value),
                                        ]
                                    )
                                    ->all()
                            )
                            ->required(),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrated(
                                fn(?string $state): bool => filled($state)
                            )
                            ->helperText(
                                'Leave empty when editing to keep the current password.'
                            ),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Avatar')
                    ->disk(
                        AssetStorageResolver::diskName(
                            AssetTypeEnum::AVATAR
                        )
                    )
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
