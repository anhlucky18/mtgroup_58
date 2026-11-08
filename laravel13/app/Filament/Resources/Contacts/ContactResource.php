<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Models\Contact;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|UnitEnum|null $navigationGroup = 'Tương tác khách hàng';

    protected static ?string $modelLabel = 'Khách hàng liên hệ';

    protected static ?string $pluralModelLabel = 'Khách hàng liên hệ';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Chi tiết liên hệ khách hàng')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Họ và tên')
                                    ->disabled(),
                                TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->disabled(),
                                TextInput::make('email')
                                    ->label('Địa chỉ Email')
                                    ->disabled(),
                                TextInput::make('address')
                                    ->label('Địa chỉ liên hệ')
                                    ->disabled(),
                                TextInput::make('subject')
                                    ->label('Tiêu đề / Dịch vụ quan tâm')
                                    ->columnSpanFull()
                                    ->disabled(),
                                Textarea::make('content')
                                    ->label('Nội dung tin nhắn khách hàng gửi')
                                    ->rows(6)
                                    ->columnSpanFull()
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép số điện thoại!'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép Email!'),
                TextColumn::make('subject')
                    ->label('Tiêu đề / Dịch vụ')
                    ->limit(35)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unread' => 'Chưa đọc',
                        'read' => 'Đã xem',
                        'replied' => 'Đã phản hồi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'unread' => 'danger',
                        'read' => 'warning',
                        'replied' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Thời gian gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'unread' => 'Chưa đọc',
                        'read' => 'Đã xem',
                        'replied' => 'Đã phản hồi',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('mark_as_read')
                    ->label('Đã xem')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->visible(fn (Contact $record): bool => $record->status === 'unread')
                    ->action(function (Contact $record) {
                        $record->update(['status' => 'read']);
                        Notification::make()
                            ->title('Đã đánh dấu đã xem')
                            ->success()
                            ->send();
                    }),
                Action::make('mark_as_replied')
                    ->label('Đã phản hồi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Contact $record): bool => $record->status !== 'replied')
                    ->action(function (Contact $record) {
                        $record->update(['status' => 'replied']);
                        Notification::make()
                            ->title('Đã đánh dấu đã phản hồi')
                            ->success()
                            ->send();
                    }),
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
            'index' => ListContacts::route('/'),
        ];
    }
}
