<?php

namespace App\Filament\Admin\Pages;

use App\Enums\AssetTypeEnum;
use App\Models\AboutMe as AboutMeModel;
use App\Services\AssetService;
use App\Support\AssetStorageResolver;
use App\Support\AssetUrl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class AboutMe extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'About Me';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'About Me';

    protected static string $view = 'filament.admin.pages.about-me';

    public ?array $data = [];

    public AboutMeModel $aboutMe;

    public function mount(): void
    {
        $this->aboutMe = AboutMeModel::query()->firstOrFail();

        $data = $this->aboutMe->toArray();

        /*
         * new_profile_image is intentionally separate from portfolio_image.
         * The existing image is displayed independently and is only replaced
         * when a new file is uploaded.
         */
        $data['new_profile_image'] = null;

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile')
                    ->description('Manage your profile information and profile image.')
                    ->schema([
                        Forms\Components\Placeholder::make('current_profile_image')
                            ->label('Current Profile Image')
                            ->content(fn() => $this->getCurrentProfileImage())
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('new_profile_image')
                            ->label('Replace Profile Image')
                            ->disk(AssetStorageResolver::diskName(AssetTypeEnum::PROFILE))
                            ->directory(AssetStorageResolver::directory(AssetTypeEnum::PROFILE))
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(2048)
                            ->visibility('public')
                            ->helperText(
                                'Leave this empty if you want to keep the current image.'
                            )
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('About')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('summary')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Professional Information')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('years_of_experience')
                            ->label('Years of Experience')
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('currently_learning')
                            ->label('Currently Learning')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('availability')
                            ->label('Available for Work'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Languages')
                    ->schema([
                        Forms\Components\KeyValue::make('language')
                            ->label('')
                            ->keyLabel('Language')
                            ->valueLabel('Level')
                            ->addActionLabel('Add language')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Links')
                    ->schema([
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('github_url')
                            ->label('GitHub URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('cv_url')
                            ->label('CV URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(AssetService $assetService): void
    {
        $data = $this->form->getState();

        $newProfileImage = $data['new_profile_image'] ?? null;

        unset(
            $data['new_profile_image'],
            $data['portfolio_image']
        );

        $oldProfileImage = $this->aboutMe->portfolio_image;

        if ($newProfileImage) {
            $data['portfolio_image'] = $newProfileImage;
        }

        $this->aboutMe->update($data);

        if ($newProfileImage) {
            $this->deleteOldProfileImage(
                assetService: $assetService,
                oldImage: $oldProfileImage,
                newImage: $newProfileImage,
            );
        }

        $this->aboutMe->refresh();

        $this->form->fill([
            ...$this->aboutMe->toArray(),
            'new_profile_image' => null,
        ]);

        Notification::make()
            ->title('About Me updated successfully')
            ->success()
            ->send();
    }

    protected function getCurrentProfileImage(): HtmlString|string
    {
        $image = $this->aboutMe->portfolio_image;

        if (! $image) {
            return 'No profile image is currently set.';
        }

        $url = AssetUrl::url($image, AssetTypeEnum::PROFILE);

        return new HtmlString(
            sprintf(
                '<img
                src="%s"
                alt="Current profile image"
                style="
                    width: 180px;
                    height: 180px;
                    object-fit: cover;
                    border-radius: 12px;
                "
            >',
                e($url)
            )
        );
    }

    protected function deleteOldProfileImage(AssetService $assetService, ?string $oldImage, string $newImage): void
    {
        if (blank($oldImage) || $oldImage === $newImage) {
            return;
        }

        $assetService->delete(
            $oldImage,
            AssetTypeEnum::PROFILE
        );
    }
}
