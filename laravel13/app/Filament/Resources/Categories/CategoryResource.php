<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Rules\UniqueCrossSlug;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static string|UnitEnum|null $navigationGroup = 'Quản lý nội dung';

    protected static ?string $modelLabel = 'Chuyên mục';

    protected static ?string $pluralModelLabel = 'Chuyên mục';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Thông tin chuyên mục')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên chuyên mục')
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
                                        Select::make('parent_id')
                                            ->label('Chuyên mục cha')
                                            ->relationship(
                                                name: 'parent',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query, ?Category $record) => $record?->id ? $query->where('id', '!=', $record->id) : $query
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Chọn chuyên mục cha (hoặc để trống nếu là cấp 1)'),
                                        Textarea::make('description')
                                            ->label('Mô tả chuyên mục')
                                            ->rows(3),
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
                                Section::make('Cài đặt & Hiển thị')
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
                                        TextInput::make('order')
                                            ->label('Thứ tự sắp xếp')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        TextInput::make('icon')
                                            ->label('Font Icon Class')
                                            ->placeholder('ti-layers hoặc fi flaticon-worker')
                                            ->maxLength(60),
                                        Toggle::make('is_featured')
                                            ->label('Nổi bật trang chủ')
                                            ->default(false),
                                    ]),

                                Section::make('Hình ảnh đại diện')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Ảnh danh mục')
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
                    ->label('Tên chuyên mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray'),
                TextColumn::make('parent.name')
                    ->label('Chuyên mục cha')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->label('Số bài viết')
                    ->counts('posts')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
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
            ])
            ->filters([
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
