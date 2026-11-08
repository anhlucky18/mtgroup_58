<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use App\Rules\UniqueCrossSlug;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Quản lý nội dung';

    protected static ?string $modelLabel = 'Bài viết';

    protected static ?string $pluralModelLabel = 'Bài viết & Tin tức';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Thông tin bài viết')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tiêu đề bài viết')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                if ($operation === 'create') {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),
                                        TextInput::make('slug')
                                            ->label('Đường dẫn tĩnh (Slug)')
                                            ->required()
                                            ->maxLength(255)
                                            ->rules([
                                                fn ($record) => new UniqueCrossSlug($record),
                                            ]),
                                        Textarea::make('description')
                                            ->label('Mô tả tóm tắt')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),

                                Section::make('Nội dung chi tiết')
                                    ->schema([
                                        Toggle::make('raw_html_mode')
                                            ->label('Chế độ xem & sửa mã HTML thô / Shortcodes')
                                            ->helperText('Bật tùy chọn này để sửa trực tiếp mã HTML mà không bị can thiệp bởi trình soạn thảo trực quan.')
                                            ->dehydrated(false)
                                            ->live(),
                                        Textarea::make('content')
                                            ->label('Mã HTML thô')
                                            ->rows(20)
                                            ->extraAttributes(['class' => 'font-mono text-sm'])
                                            ->visible(fn ($get) => (bool) $get('raw_html_mode')),
                                        RichEditor::make('content')
                                            ->label('Nội dung soạn thảo')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('uploads')
                                            ->visible(fn ($get) => ! (bool) $get('raw_html_mode')),
                                    ]),

                                Section::make('Thư viện hình ảnh (Gallery)')
                                    ->schema([
                                        Repeater::make('gallery')
                                            ->label('Danh sách ảnh thư viện')
                                            ->schema([
                                                FileUpload::make('img')
                                                    ->label('Hình ảnh')
                                                    ->disk('public')
                                                    ->directory('uploads')
                                                    ->image()
                                                    ->formatStateUsing(fn ($state) => $state ? preg_replace('#^/?storage/#', '', $state) : null)
                                                    ->dehydrateStateUsing(fn ($state) => $state ? ('/storage/' . ltrim(preg_replace('#^/?storage/#', '', $state), '/')) : null)
                                                    ->columnSpan(1),
                                                TextInput::make('description')
                                                    ->label('Chú thích ảnh')
                                                    ->maxLength(255)
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->defaultItems(0),
                                    ]),

                                Section::make('Tối ưu hóa SEO')
                                    ->schema([
                                        TextInput::make('seo_title')
                                            ->label('SEO Title')
                                            ->maxLength(70)
                                            ->helperText('Tối đa 70 ký tự'),
                                        Textarea::make('seo_description')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Tối đa 160 ký tự'),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Xuất bản & Phân loại')
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Ngôn ngữ')
                                            ->options([
                                                'vi' => 'Tiếng Việt',
                                                'en_US' => 'English',
                                            ])
                                            ->default('vi')
                                            ->required(),
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'publish' => 'Xuất bản',
                                                'draft' => 'Bản nháp',
                                            ])
                                            ->default('publish')
                                            ->required(),
                                        DateTimePicker::make('published_at')
                                            ->label('Thời gian xuất bản')
                                            ->default(now()),
                                        Toggle::make('is_featured')
                                            ->label('Bài viết nổi bật')
                                            ->default(false),
                                        Select::make('categories')
                                            ->label('Chuyên mục')
                                            ->relationship('categories', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                        Select::make('tags')
                                            ->label('Từ khóa')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Tên từ khóa')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                                TextInput::make('slug')
                                                    ->label('Slug')
                                                    ->required(),
                                                Select::make('locale')
                                                    ->label('Ngôn ngữ')
                                                    ->options([
                                                        'vi' => 'Tiếng Việt',
                                                        'en_US' => 'English',
                                                    ])
                                                    ->default('vi'),
                                            ]),
                                    ]),

                                Section::make('Ảnh đại diện')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Ảnh bài viết')
                                            ->disk('public')
                                            ->directory('uploads')
                                            ->image()
                                            ->formatStateUsing(fn ($state) => $state ? preg_replace('#^/?storage/#', '', $state) : null)
                                            ->dehydrateStateUsing(fn ($state) => $state ? ('/storage/' . ltrim(preg_replace('#^/?storage/#', '', $state), '/')) : null),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Ảnh')
                    ->disk('public')
                    ->state(fn ($record) => $record->image ? preg_replace('#^/?storage/#', '', $record->image) : null)
                    ->square()
                    ->imageSize(50),
                TextColumn::make('name')
                    ->label('Tiêu đề')
                    ->limit(60)
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('categories.name')
                    ->label('Chuyên mục')
                    ->badge()
                    ->color('info')
                    ->limitList(2),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('views')
                    ->label('Lượt xem')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                ToggleColumn::make('is_featured')
                    ->label('Nổi bật'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'publish' => 'success',
                        'draft' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'publish' => 'Xuất bản',
                        'draft' => 'Bản nháp',
                        default => $state,
                    }),
                TextColumn::make('published_at')
                    ->label('Xuất bản lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->label('Chuyên mục')
                    ->relationship('categories', 'name'),
                SelectFilter::make('locale')
                    ->label('Ngôn ngữ')
                    ->options([
                        'vi' => 'Tiếng Việt',
                        'en_US' => 'English',
                    ]),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'publish' => 'Xuất bản',
                        'draft' => 'Bản nháp',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Bài viết nổi bật'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
