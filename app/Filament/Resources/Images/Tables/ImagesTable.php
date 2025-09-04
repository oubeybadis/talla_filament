<?php

namespace App\Filament\Resources\Images\Tables;

use App\Models\UploadedImage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
            ImageColumn::make('file_path')
                    ->label('Image')
                    ->size(80)
                    ->square(),
                    
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                    
                TextColumn::make('formatted_size')
                    ->label('Size')
                    ->sortable('file_size'),
                    
                ToggleColumn::make('is_favorite')
                    ->label('Favorite'),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('favorites')
                    ->query(fn (Builder $query): Builder => $query->where('is_favorite', true))
                    ->label('Favorites only')
            ])
            ->actions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (UploadedImage $record) {
                        if (!Storage::exists($record->file_path)) {
                            Notification::make()
                                ->title('File not found')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        return Storage::download(
                            $record->file_path, 
                            $record->title . '.' . pathinfo($record->file_path, PATHINFO_EXTENSION)
                        );
                    }),
                    
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn (UploadedImage $record) => $record->image_url)
                    ->openUrlInNewTab(),
                    
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ])
            
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }
   
}
