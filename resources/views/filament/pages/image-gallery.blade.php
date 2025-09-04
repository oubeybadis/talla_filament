@push('styles')
    @vite(['resources/css/app.css'])
@endpush

<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Art Gallery</h1>
                <p class="text-sm text-gray-600 mt-1">Discover beautiful artworks from the Art Institute of Chicago</p>
            </div>
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.500ms="search"
                        placeholder="Search artworks..."
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

        <!-- Loading State -->
        <div wire:loading class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>

        <!-- Image Grid -->
        <div wire:loading.remove class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" x-data="imageGallery()">
            @forelse($artworks as $artwork)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image Container -->
                    <div class="relative aspect-square overflow-hidden group">
                        <img 
                            src="{{ $artwork['thumbnail_url'] }}"
                            alt="{{ $artwork['title'] }}"
                            class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-110"
                            x-on:click="showImage('{{ $artwork['full_url'] }}', '{{ addslashes($artwork['title']) }}')"
                            loading="lazy"
                            onerror="this.src='/images/placeholder.jpg'"
                        />
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Action Buttons Overlay -->
                        <div class="absolute top-3 right-3 flex space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <!-- Favorite Button -->
                            <button 
                                wire:click="toggleFavorite('{{ $artwork['id'] }}', '{{ addslashes($artwork['title']) }}', '{{ $artwork['full_url'] }}')"
                                class="p-2.5 rounded-full {{ $artwork['is_favorite'] ? 'bg-red-500 text-white' : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600' }} shadow-lg hover:scale-110 transition-all duration-200 backdrop-blur-sm"
                                title="{{ $artwork['is_favorite'] ? 'Remove from favorites' : 'Add to favorites' }}"
                            >
                                <svg class="w-4 h-4" fill="{{ $artwork['is_favorite'] ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            
                            <!-- Download Button -->
                            <button 
                                wire:click="downloadImage('{{ $artwork['image_id'] }}', '{{ addslashes($artwork['title']) }}')"
                                class="p-2.5 rounded-full bg-white/90 text-gray-700 shadow-lg hover:scale-110 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 backdrop-blur-sm"
                                title="Download image"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Quick View Button -->
                        <div class="absolute bottom-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button 
                                x-on:click="showImage('{{ $artwork['full_url'] }}', '{{ addslashes($artwork['title']) }}')"
                                class="px-3 py-1.5 bg-white/90 text-gray-700 text-xs font-medium rounded-full shadow-lg hover:bg-white hover:scale-105 transition-all duration-200 backdrop-blur-sm"
                            >
                                Quick View
                            </button>
                        </div>
                    </div>
                    
                    <!-- Image Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 leading-tight">
                            {{ $artwork['title'] }}
                        </h3>
                        @if($artwork['artist'])
                            <p class="text-xs text-gray-600 mb-1 line-clamp-1">{{ $artwork['artist'] }}</p>
                        @endif
                        @if($artwork['date'])
                            <p class="text-xs text-gray-500">{{ $artwork['date'] }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <!-- No Results -->
                <div class="col-span-full text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No artworks found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search terms or browse our collection.</p>
                    <button 
                        wire:click="$set('search', '')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Clear Search
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(count($artworks) > 0)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Showing {{ count($artworks) }} artworks
                    @if(isset($pagination['total']))
                        of {{ number_format($pagination['total']) }} total
                    @endif
                </div>
                
                <div class="flex items-center space-x-2">
                    <button 
                        wire:click="previousPage"
                        @if($this->getPage() <= 1) disabled @endif
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>
                    
                    <span class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-lg">
                        Page {{ $this->getPage() }} 
                        @if(isset($pagination['total']))
                            of {{ ceil($pagination['total'] / 20) }}
                        @endif
                    </span>
                    
                    <button 
                        wire:click="nextPage"
                        @if($this->getPage() >= ceil(($pagination['total'] ?? 0) / 20)) disabled @endif
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        Next
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
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
        function imageGallery() {
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