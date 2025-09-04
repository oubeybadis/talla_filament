@push('styles')
    @vite(['resources/css/app.css'])
@endpush

<x-filament-panels::page>
    <div class="space-y-6" x-data="favoritesGallery()">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Favorites</h1>
                <p class="text-sm text-gray-600 mt-1">Your collection of saved artworks and images</p>
            </div>
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.500ms="search"
                        placeholder="Search favorites..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        @if(count($favorites) > 0)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ count($favorites) }}</div>
                            <div class="text-sm text-blue-500">Total Favorites</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $favorites->where('type', 'user')->count() }}</div>
                            <div class="text-sm text-green-500">My Uploads</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $favorites->where('type', 'gallery')->count() }}</div>
                            <div class="text-sm text-purple-500">Gallery Items</div>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading State -->
        <div wire:loading class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>

        <!-- Favorites Grid -->
        <div wire:loading.remove class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($favorites as $favorite)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image Container -->
                    <div class="relative aspect-square overflow-hidden group">
                        <img 
                            src="{{ $favorite['image_url'] }}"
                            alt="{{ $favorite['title'] }}"
                            class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-110"
                            x-on:click="showImage('{{ $favorite['image_url'] }}', '{{ addslashes($favorite['title']) }}')"
                            loading="lazy"
                            onerror="this.src='/images/placeholder.jpg'"
                        />
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Action Buttons Overlay -->
                        <div class="absolute top-3 right-3 flex space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <!-- Remove Favorite Button -->
                            <button 
                                wire:click="removeFavorite('{{ $favorite['id'] }}', '{{ $favorite['type'] }}')"
                                class="p-2.5 rounded-full bg-red-500 text-white shadow-lg hover:scale-110 hover:bg-red-600 transition-all duration-200 backdrop-blur-sm"
                                title="Remove from favorites"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <!-- Download Button -->
                            <button 
                                wire:click="downloadImage('{{ $favorite['image_url'] }}', '{{ addslashes($favorite['title']) }}')"
                                class="p-2.5 rounded-full bg-white/90 text-gray-700 shadow-lg hover:scale-110 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 backdrop-blur-sm"
                                title="Download image"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $favorite['type'] === 'user' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                {{ $favorite['type'] === 'user' ? 'My Upload' : 'Gallery' }}
                            </span>
                        </div>

                        <!-- Quick View Button -->
                        <div class="absolute bottom-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button 
                                x-on:click="showImage('{{ $favorite['image_url'] }}', '{{ addslashes($favorite['title']) }}')"
                                class="px-3 py-1.5 bg-white/90 text-gray-700 text-xs font-medium rounded-full shadow-lg hover:bg-white hover:scale-105 transition-all duration-200 backdrop-blur-sm"
                            >
                                Quick View
                            </button>
                        </div>
                    </div>
                    
                    <!-- Image Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 leading-tight">
                            {{ $favorite['title'] }}
                        </h3>
                        @if($favorite['description'])
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $favorite['description'] }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ $favorite['created_at']->diffForHumans() }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Favorite
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <!-- No Favorites State -->
                <div class="col-span-full text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($search)
                            No favorites match your search
                        @else
                            No favorites yet
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-4">
                        @if($search)
                            Try adjusting your search terms or browse your collection.
                        @else
                            Start adding images to your favorites from the Gallery or My Images pages.
                        @endif
                    </p>
                    @if($search)
                        <button 
                            wire:click="$set('search', '')"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Clear Search
                        </button>
                    @else
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a 
                                href="{{ route('filament.admin.pages.image-gallery') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Browse Gallery
                            </a>
                            <a 
                                href="{{ route('filament.admin.pages.my-images') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Upload Images
                            </a>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <!-- Enhanced Image Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-on:click="closeModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="max-w-6xl max-h-full overflow-hidden rounded-2xl shadow-2xl" x-on:click.stop x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="relative">
                <img :src="modalImage" :alt="modalTitle" class="max-w-full max-h-[80vh] object-contain" />
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                    <h3 class="text-white text-lg font-semibold mb-2" x-text="modalTitle"></h3>
                </div>
            </div>
            <button x-on:click="closeModal" class="absolute top-4 right-4 p-2 bg-black/50 text-white rounded-full hover:bg-black/70 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        function favoritesGallery() {
            return {
                showModal: false,
                modalImage: '',
                modalTitle: '',
                
                showImage(imageUrl, title) {
                    this.modalImage = imageUrl;
                    this.modalTitle = title;
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                },
                
                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = 'auto';
                }
            }
        }
    </script>
</x-filament-panels::page>