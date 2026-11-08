<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Rules\UniqueCrossSlug;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|UnitEnum|null $navigationGroup = 'Quản lý nội dung';

    protected static ?string $modelLabel = 'Trang tĩnh';

    protected static ?string $pluralModelLabel = 'Trang tĩnh';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Thông tin trang')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tiêu đề trang')
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
                                            ->label('Mô tả ngắn')
                                            ->rows(3),
                                    ]),

                                Section::make('Nội dung trang chi tiết')
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
                                            ->maxLength(70),
                                        Textarea::make('seo_description')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(3),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Cài đặt & Giao diện')
                                    ->schema([
                                        Select::make('template')
                                            ->label('Mẫu giao diện (Template)')
                                            ->options([
                                                'default' => 'Mặc định (Standard Content)',
                                                'services' => 'Dịch vụ quan trắc & môi trường',
                                                'products' => 'Danh mục sản phẩm thiết bị',
                                                'projects' => 'Dự án tiêu biểu',
                                                'inquiry' => 'Form khảo sát & Liên hệ nhanh',
                                            ])
                                            ->default('default')
                                            ->required(),
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
                                    ]),

                                Section::make('Hình ảnh đại diện')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Ảnh đại diện trang')
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
                TextColumn::make('name')
                    ->label('Tiêu đề trang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray'),
                TextColumn::make('template')
                    ->label('Mẫu giao diện')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'default' => 'Mặc định',
                        'services' => 'Dịch vụ',
                        'products' => 'Sản phẩm',
                        'projects' => 'Dự án',
                        'inquiry' => 'Khảo sát / Inquiry',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'default' => 'gray',
                        'services' => 'info',
                        'products' => 'warning',
                        'projects' => 'primary',
                        'inquiry' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
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
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('Ngôn ngữ')
                    ->options([
                        'vi' => 'Tiếng Việt',
                        'en_US' => 'English',
                    ]),
                SelectFilter::make('template')
                    ->label('Mẫu giao diện')
                    ->options([
                        'default' => 'Mặc định',
                        'services' => 'Dịch vụ',
                        'products' => 'Sản phẩm',
                        'projects' => 'Dự án',
                        'inquiry' => 'Khảo sát / Inquiry',
                    ]),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'publish' => 'Xuất bản',
                        'draft' => 'Bản nháp',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
