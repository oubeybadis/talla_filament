<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                Textarea::make('description')
                    ->required()
                    ->maxLength(1000)
                    ->rows(4)
                    ->columnSpanFull(),
                    
                FileUpload::make('file_path')
                    ->label('Image')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(6144) // 6MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('user-images')
                    ->storeFileNamesIn('original_filename')
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('file_size', $state->getSize());
                        }
                    }),
                    
                Toggle::make('is_favorite')
                    ->label('Mark as favorite')
                    ->default(false),
            ]);
    }
}
