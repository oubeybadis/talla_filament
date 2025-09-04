<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class ApiService
{
    private string $baseUrl = 'https://api.artic.edu/api/v1';
    private string $iiifBaseUrl = 'https://www.artic.edu/iiif/2';
    
    public function getArtworks(int $page = 1, ?string $search = null, int $limit = 20): array
    {
        try {
            $cacheKey = "artworks_page_{$page}_search_" . md5($search ?? '') . "_limit_{$limit}";
            
            return Cache::remember($cacheKey, 300, function () use ($page, $search, $limit) {
                $params = [
                    'page' => $page,
                    'limit' => $limit,
                    'fields' => 'id,title,artist_display,date_display,image_id,thumbnail'
                ];

                if ($search) {
                    $url = $this->baseUrl . '/artworks/search';
                    $params['q'] = $search;
                    $params['query'] = [
                        'term' => [
                            'is_public_domain' => true
                        ]
                    ];
                } else {
                    $url = $this->baseUrl . '/artworks';
                    $params['ids'] = $this->getPublicDomainIds();
                }

                $response = Http::timeout(30)->get($url, $params);

                if ($response->successful()) {
                    return $response->json();
                }

                throw new Exception('API request failed: ' . $response->status());
            });
        } catch (Exception $e) {
            \Log::error('Art Institute API Error: ' . $e->getMessage());
            return [
                'data' => [],
                'pagination' => ['total' => 0, 'current_page' => 1]
            ];
        }
    }

    public function getImageUrl(string $imageId, int $width = 843): string
    {
        if (!$imageId) {
            return '/images/placeholder.jpg';
        }

        return "{$this->iiifBaseUrl}/{$imageId}/full/{$width},/0/default.jpg";
    }

    public function getThumbnailUrl(string $imageId, int $width = 200): string
    {
        return $this->getImageUrl($imageId, $width);
    }

    public function downloadImage(string $imageId, string $title): string
    {
        try {
            $imageUrl = $this->getImageUrl($imageId, 1686);
            $response = Http::timeout(60)->get($imageUrl);
            
            if ($response->successful()) {
                $filename = \Str::slug($title) . '.jpg';
                $tempPath = storage_path('app/temp/' . $filename);
                
                if (!file_exists(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                
                file_put_contents($tempPath, $response->body());
                return $tempPath;
            }
            
            throw new Exception('Failed to download image');
        } catch (Exception $e) {
            \Log::error('Image download error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getPublicDomainIds(): string
    {
        // Cache popular public domain artwork IDs
        return Cache::remember('public_domain_ids', 3600, function () {
            try {
                $response = Http::get($this->baseUrl . '/artworks', [
                    'limit' => 100,
                    'fields' => 'id',
                    'query' => [
                        'term' => ['is_public_domain' => true]
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $ids = collect($data['data'])->pluck('id')->toArray();
                    return implode(',', $ids);
                }
            } catch (Exception $e) {
                \Log::error('Error fetching public domain IDs: ' . $e->getMessage());
            }

            // Fallback IDs
            return '27992,28067,111628,16568,111435';
        });
    }
}
