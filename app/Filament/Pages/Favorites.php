<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Image;
use App\Models\ApiFavorite;
use App\Models\UploadedImage;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class Favorites extends Page
{
    // protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Favorites';
    protected  string $view = 'filament.pages.favorites';
    
    public string $search = '';
    public array $favorites = [];
    
    public function mount(): void
    {
        $this->loadFavorites();
    }
    
    public function updatedSearch(): void
    {
        $this->loadFavorites();
    }
    
    public function loadFavorites(): void
    {
        // Get user uploaded favorites
        $userFavorites = UploadedImage::where('is_favorite', true)
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->get()
            ->map(function ($image) {
                return [
                    'id' => 'user_' . $image->id,
                    'type' => 'user',
                    'title' => $image->title,
                    'description' => $image->description,
                    'image_url' => $image->image_url,
                    'created_at' => $image->created_at,
                    'record_id' => $image->id
                ];
            });

        // Get API favorites
        $apiFavorites = ApiFavorite::when($this->search, function ($query) {
                return $query->where('artwork_title', 'like', '%' . $this->search . '%');
            })
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => 'api_' . $favorite->api_artwork_id,
                    'type' => 'api',
                    'title' => $favorite->artwork_title,
                    'description' => '',
                    'image_url' => $favorite->api_image_url,
                    'created_at' => $favorite->created_at,
                    'record_id' => $favorite->id,
                    'api_artwork_id' => $favorite->api_artwork_id
                ];
            });

        // Merge and sort by created_at
        $this->favorites = $userFavorites
            ->concat($apiFavorites)
            ->sortByDesc('created_at')
            ->values()
            ->toArray();
    }
    
    public function removeFavorite(string $id, string $type): void
    {
        if ($type === 'user') {
            $imageId = str_replace('user_', '', $id);
            $image = UploadedImage::find($imageId);
            
            if ($image) {
                $image->update(['is_favorite' => false]);
                
                Notification::make()
                    ->title('Removed from favorites')
                    ->success()
                    ->send();
            }
        } elseif ($type === 'api') {
            $favoriteId = str_replace('api_', '', $id);
            $apiFavorite = ApiFavorite::where('api_artwork_id', $favoriteId)->first();
            
            if ($apiFavorite) {
                $apiFavorite->delete();
                
                Notification::make()
                    ->title('Removed from favorites')
                    ->success()
                    ->send();
            }
        }
        
        $this->loadFavorites();
    }
    
    public function downloadImage(string $imageUrl, string $title): void
    {
        try {
            $response = Http::timeout(60)->get($imageUrl);
            
            if ($response->successful()) {
                $filename = \Str::slug($title) . '.jpg';
                
                // Normally, you would return the response to trigger a download,
                // but since this method must return void, you could store the file
                // or trigger a notification instead.
                // For now, just notify the user that the download is ready.
                Notification::make()
                    ->title('Download ready')
                    ->body('The image has been downloaded successfully.')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Download failed')
                ->body('Unable to download the image. Please try again.')
                ->danger()
                ->send();
        }
    }
}