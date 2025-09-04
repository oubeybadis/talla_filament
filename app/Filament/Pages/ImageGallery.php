<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\ApiService;
use App\Models\ApiFavorite;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\WithPagination;

class ImageGallery extends Page
{
    use WithPagination;
    
    // protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';
    protected string $view = 'filament.pages.image-gallery';
    
    public string $search = '';
    public array $artworks = [];
    public array $pagination = [];
    public array $favorites = [];
    
    protected ApiService $apiService;
    
    public function boot(): void
    {
        $this->apiService = app(ApiService::class);
    }
    
    public function mount(): void
    {   
        // dd($this->loadFavorites());
        $this->loadArtworks();
        $this->loadFavorites();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('search')
                    ->label('Search artworks')
                    ->placeholder('Enter search term...')
                    ->live()
                    ->debounce(500),
            ]);
    }
    
    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->loadArtworks();
    }
    
    public function loadArtworks(): void
    {
        $page = $this->getPage();
        $response = $this->apiService->getArtworks($page, $this->search ?: null, 20);
        
        $this->artworks = collect($response['data'])->map(function ($artwork) {
            return [
                'id' => $artwork['id'],
                'title' => $artwork['title'] ?? 'Untitled',
                'artist' => $artwork['artist_display'] ?? 'Unknown Artist',
                'date' => $artwork['date_display'] ?? '',
                'image_id' => $artwork['image_id'] ?? null,
                'thumbnail_url' => $this->apiService->getThumbnailUrl($artwork['image_id'] ?? ''),
                'full_url' => $this->apiService->getImageUrl($artwork['image_id'] ?? '', 843),
                'is_favorite' => in_array($artwork['id'], $this->favorites)
            ];
        })->filter(fn($artwork) => $artwork['image_id'])->toArray();
        
        $this->pagination = $response['pagination'] ?? ['total' => 0, 'current_page' => 1];
    }
    
    public function loadFavorites(): void
    {
        $this->favorites = ApiFavorite::pluck('api_artwork_id')->toArray();
    }
    
    public function toggleFavorite(string $artworkId, string $title, string $imageUrl): void
    {
        $favorite = ApiFavorite::where('api_artwork_id', $artworkId)->first();
        
        if ($favorite) {
            $favorite->delete();
            $this->favorites = array_diff($this->favorites, [$artworkId]);
            
            Notification::make()
                ->title('Removed from favorites')
                ->success()
                ->send();
        } else {
            ApiFavorite::create([
                'api_artwork_id' => $artworkId,
                'artwork_title' => $title,
                'api_image_url' => $imageUrl,
            ]);
            
            $this->favorites[] = $artworkId;
            
            Notification::make()
                ->title('Added to favorites')
                ->success()
                ->send();
        }
    }
    
    public function downloadImage(string $imageId, string $title): void
    {
        try {
            $tempPath = $this->apiService->downloadImage($imageId, $title);
            $filename = basename($tempPath);
            
            response()->download($tempPath, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Download failed')
                ->body('Unable to download the image. Please try again.')
                ->danger()
                ->send();
        }
    }
    
    public function nextPage(): void
    {
        $this->setPage($this->getPage() + 1);
        $this->loadArtworks();
    }
    
    public function previousPage(): void
    {
        if ($this->getPage() > 1) {
            $this->setPage($this->getPage() - 1);
            $this->loadArtworks();
        }
    }
    
    protected function getHeaderActions(): array
    {
        return [
            // You can add header actions here if needed
        ];
    }
}