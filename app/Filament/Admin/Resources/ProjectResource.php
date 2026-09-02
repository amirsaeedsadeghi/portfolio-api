<?php

namespace App\Filament\Admin\Resources;

use App\Enums\AssetTypeEnum;
use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Support\AssetStorageResolver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?string $navigationLabel = 'Projects';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('role')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('client')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Textarea::make('summary')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Links')
                    ->schema([
                        Forms\Components\TextInput::make('demo_link')
                            ->label('Demo URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('github')
                            ->label('GitHub URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Technology Stacks')
                    ->schema([
                        Forms\Components\Select::make('stacks')
                            ->relationship('stacks', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Primary Image')
                    ->schema([
                        Forms\Components\FileUpload::make('primary_image')
                            ->label('Primary Image')
                            ->disk(
                                AssetStorageResolver::diskName(
                                    AssetTypeEnum::PROJECT
                                )
                            )
                            ->directory(
                                AssetStorageResolver::directory(
                                    AssetTypeEnum::PROJECT
                                )
                            )
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(4096)
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Project Gallery')
                    ->description('Manage additional images displayed in the project gallery.')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->disk(
                                        AssetStorageResolver::diskName(
                                            AssetTypeEnum::PROJECT
                                        )
                                    )
                                    ->directory(
                                        AssetStorageResolver::directory(
                                            AssetTypeEnum::PROJECT
                                        )
                                    )
                                    ->image()
                                    ->imageEditor()
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                    ])
                                    ->maxSize(4096)
                                    ->visibility('public')
                                    ->required(),

                                Forms\Components\TextInput::make('alt')
                                    ->label('Alternative Text')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->orderColumn('order')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                $state['alt'] ?? 'Project Image'
                            )
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primary_image')
                    ->label('Image')
                    ->disk(
                        AssetStorageResolver::diskName(
                            AssetTypeEnum::PROJECT
                        )
                    )
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
